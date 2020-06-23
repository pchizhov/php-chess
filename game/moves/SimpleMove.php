<?php


class SimpleMove extends Move {

    private ?PieceManager $captured_piece = null;

    public function __construct(Cell &$from, Cell &$to, PieceManager $captured_piece = null) {
        parent::__construct($from, $to);
        if (isset($captured_piece)) {
            $this->captured_piece = $captured_piece;
        }
    }

    public function execute(): void {
        if (!$this->to->is_empty() && $this->can_capture()) {
            $this->captured_piece = $this->to->get_piece();
            $this->captured_piece->capture();
        }
        $this->to->set_piece($this->from->get_piece());
        $this->from->remove_piece();
    }

    public function undo(): void {
        $this->from->set_piece($this->to->get_piece());
        $this->to->remove_piece();
        if ($this->captured_piece) {
            $this->to->set_piece($this->captured_piece);
            $this->captured_piece->put_back();
        }
        $this->captured_piece = null;
    }

    public function get_type(): int {
        return $this->to->is_empty() ? MoveEnum::FORWARD_MOVE : MoveEnum::CAPTURE_MOVE;
    }

    public function to_json() {
        $result = json_decode('{}');
        $result->to = $this->to->get_coordinates();
        $result->from = $this->from->get_coordinates();
        $result->type = $this->get_type();
        return $result;
    }

    private function can_capture(): bool {
        return !$this->to->is_empty() &&
            $this->from->get_piece()->get_color() != $this->to->get_piece()->get_color();
    }

    public function threatens_the_king(): bool {
        return $this->can_capture() and $this->to->get_piece()->get_piece_type() == PieceEnum::KING;
    }

}
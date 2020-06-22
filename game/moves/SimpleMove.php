<?php


class SimpleMove extends Move {

    private ?PieceManager $captured_piece = null;

    public function execute(): void {
        if (!$this->to->is_empty() and $this->can_capture()) {
            $this->captured_piece = $this->to->get_piece();
            $this->captured_piece->capture();
        }
        $this->to->set_piece($this->from->get_piece());
        $this->from->remove_piece();
    }

    public function undo(): void {
        if ($this->captured_piece) {
            $this->to->set_piece($this->captured_piece);
            $this->captured_piece->put_back();
        }
        $this->from->set_piece($this->to->get_piece());
        $this->to->remove_piece();
        $this->captured_piece = null;
    }

    private function can_capture(): bool {
        return !$this->to->is_empty() and
            $this->from->get_piece()->get_color() != $this->to->get_piece()->get_color();
    }

    public function threatens_the_king(): bool {
        return $this->can_capture() and $this->to->get_piece()->is_a_king();
    }

}
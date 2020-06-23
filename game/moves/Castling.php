<?php


class Castling extends SimpleMove {

    private SimpleMove $king_move;

    public function __construct(Cell &$from, Cell &$to, Cell &$king_from, Cell &$king_to) {
        parent::__construct($from, $to);
        $this->king_move = new SimpleMove($king_from, $king_to);
    }

    public function execute(): void {
        parent::execute();
        $this->king_move->execute();
    }

    public function undo(): void {
        parent::undo();
        $this->king_move->undo();
    }

    public function get_king_from(): Cell {
        return $this->king_move->get_from();
    }

    public function get_king_to(): Cell {
        return $this->king_move->get_to();
    }

    public function get_type(): int {
        return MoveEnum::CASTLING;
    }

    public function to_json() {
        $result = json_decode('{}');
        $result->rook_from = $this->from->get_coordinates();
        $result->rook_to = $this->to->get_coordinates();
        $result->king_from = $this->get_king_from()->get_coordinates();
        $result->king_to = $this->get_king_to()->get_coordinates();
        $result->type = MoveEnum::CASTLING;
        return $result;
    }

}
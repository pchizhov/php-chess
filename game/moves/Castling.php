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

}
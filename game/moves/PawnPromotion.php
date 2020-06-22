<?php


class PawnPromotion extends SimpleMove {

    private int $piece_type;
    private PieceManager $pawn;

    public function __construct(Cell $from, Cell $to, int $piece_type) {
        parent::__construct($from, $to);
        $this->piece_type = $piece_type;
    }

    public function execute(): void {
        parent::execute();
        $this->pawn = $this->to->get_piece();
        $this->pawn->capture();
        $this->to->set_piece(Game::create_piece($this->piece_type, $this->pawn->get_color(), $this->to->get_coordinates()));
    }

    public function undo(): void {
        $this->to->get_piece()->capture();
        $this->to->set_piece($this->pawn);
        $this->pawn->put_back();
        parent::undo();
    }
}
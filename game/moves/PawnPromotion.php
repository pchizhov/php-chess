<?php


class PawnPromotion extends SimpleMove {

    private int $piece_type;
    private PieceManager $pawn;

    public function __construct(Cell &$from, Cell &$to, int $piece_type, PieceManager $pawn = null) {
        parent::__construct($from, $to);
        $this->piece_type = $piece_type;
        if (isset($pawn)) {
            $this->pawn = $pawn;
        }
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

    public function get_type(): int {
        return MoveEnum::PAWN_PROMOTION;
    }

    public function get_new_piece_type(): int {
        return $this->piece_type;
    }

    public function to_json() {
        $result = parent::to_json();
        $result->new_piece = $this->piece_type;
        $result->type = MoveEnum::PAWN_PROMOTION;
        return $result;
    }

}
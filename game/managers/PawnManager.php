<?php

require_once 'PieceManager.php';
require_once 'game/moves/SimpleMove.php';

class PawnManager extends PieceManager {

    public function get_moves(): array {
        $moves = [];
        foreach ($this->piece->to_move() as $shift) {
            if ($neighbour = Game::get_board()->get_neighbour($this->cell, $shift) and
                $neighbour->is_empty()) {
                if ($neighbour->get_coordinates()[0] % 7) {
                    array_push($moves, new SimpleMove($this->cell, $neighbour));
                } else {
                    $moves = array_merge($moves, $this->get_promotions($this->cell, $neighbour));
                }
            }
        }
        foreach ($this->piece->to_capture() as $shift) {
            if ($neighbour = Game::get_board()->get_neighbour($this->cell, $shift) and
                !$neighbour->is_empty() and
                $neighbour->get_piece()->get_color() != $this->piece->get_color()) {
                if ($neighbour->get_coordinates()[0] % 7) {
                    array_push($moves, new SimpleMove($this->cell, $neighbour));
                } else {
                    $moves = array_merge($moves, $this->get_promotions($this->cell, $neighbour));
                }
            }
        }
        return $moves;
    }

    private function get_promotions(Cell $from, Cell $to): array {
        return [
            new PawnPromotion($from, $to, PieceEnum::BISHOP),
            new PawnPromotion($from, $to, PieceEnum::KNIGHT),
            new PawnPromotion($from, $to, PieceEnum::ROOK),
            new PawnPromotion($from, $to, PieceEnum::QUEEN)
        ];
    }

}
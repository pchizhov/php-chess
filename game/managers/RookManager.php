<?php

require_once 'PieceManager.php';

class RookManager extends ForwardManager {

    public function all_moves(): array {
        $moves = parent::all_moves();
        if ($c = $this->check_castling()) {
            array_push($moves, $c);
        }
        return $moves;
    }

    private function check_castling(): ?Castling {
        if (!$this->piece->used()) {
            switch ($this->cell->get_coordinates()[1]) {
                case 0:
                    $direction = [0, 1];
                    $king_to = [0, 2];
                    $rook_to = [0, 3];
                    break;
                case 7:
                    $direction = [0, -1];
                    $king_to = [7, 6];
                    $rook_to = [7, 5];
                    break;
                default:
                    return null;
            }
            $current_cell = Game::get_board()->get_cell($this->cell->get_neighbour($direction));
            while ($current_cell->neighbour_exists($direction) && $current_cell->is_empty()) {
                $current_cell = Game::get_board()->get_cell($this->cell->get_neighbour($direction));
            }
            return ($current_cell->get_piece()->get_piece_type() == PieceEnum::KING &&
                !$current_cell->get_piece()->used()) ?
                new Castling(
                    $this->cell, Game::get_board()->get_cell($rook_to),
                    $current_cell, Game::get_board()->get_cell($king_to)
                ) : null;
        }
        return null;
    }

}
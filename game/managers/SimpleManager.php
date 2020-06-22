<?php


class SimpleManager extends PieceManager {

    public function all_moves(): array {
        $shifts = $this->piece->to_move();
        $moves = [];
        foreach ($shifts as $shift) {
            if ($neighbour = Game::get_board()->get_neighbour($this->cell, $shift) and ($neighbour->is_empty()
                or $neighbour->get_piece()->get_color() != $this->piece->get_color())) {
                array_push($moves, new SimpleMove($this->cell, $neighbour));
            }
        }
        return $moves;
    }

}
<?php

require_once 'PieceManager.php';


class ForwardManager extends PieceManager {

    public function all_moves(): array {
        $moves = [];
        foreach ($this->piece->to_move() as $shift) {
            $current_cell = $this->cell;
            while ($neighbour = Game::get_board()->get_neighbour($current_cell, $shift) and
                   $neighbour->is_empty()) {
                array_push($moves, new SimpleMove($this->cell, $neighbour));
                $current_cell = $neighbour;
            }
            if ($neighbour and $neighbour->get_piece()->get_color() != $this->piece->get_color()) {
                array_push($moves, new SimpleMove($this->cell, $neighbour));
            }
        }
        return $moves;
    }

}
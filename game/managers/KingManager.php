<?php


class KingManager extends SimpleManager {

    public function all_moves(): array {
        $moves = parent::all_moves();
        if ($c = $this->check_castling()) {
            $moves = array_merge($moves, $c);
        }
        return $moves;
    }

    private function check_castling(): array {
        $result = [];
        if (!$this->used()) {
            $row = $this->cell->get_coordinates()[0];
            $ways = [
                [[$row, 1], [$row, 2], [$row, 3]],
                [[$row, 5], [$row, 6]]
            ];
            $rooks = [[$row, 0], [$row, 7]];
            $to = [
                [[$row, 2], [$row, 3]],
                [[$row, 6], [$row, 5]]
            ];
            for ($i = 0; $i < 2; ++$i) {
                $to_castle = true;
                foreach ($ways[$i] as $way_cell) {
                    $to_castle &= Game::get_board()->get_cell($way_cell)->is_empty();
                }
                $to_castle &= !Game::get_board()->get_cell($rooks[$i])->is_empty() and
                              !Game::get_board()->get_cell($rooks[$i])->get_piece()->used();
                if ($to_castle) {
                    array_push($result,
                        new Castling(Game::get_board()->get_cell($rooks[$i]),
                                     Game::get_board()->get_cell($to[$i][1]),
                                     $this->cell,
                                     Game::get_board()->get_cell($to[$i][0])));
                }
            }
        }
        return $result;
    }

}
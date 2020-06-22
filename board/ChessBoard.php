<?php

require_once 'Cell.php';
require_once 'game/exceptions/NoSuchCellException.php';

class ChessBoard {

    private array $board;

    public function __construct() {
        $this->board = [];
        for ($i = 0; $i < 8; ++$i) {
            array_push($this->board, []);
            for ($j = 0; $j < 8; ++$j) {
                array_push($this->board[$i], new Cell([$i, $j]));
            }
        }
    }

    public function &get_cell(array $coordinates): Cell {
        $i = $coordinates[0];
        $j = $coordinates[1];
        if ($i < 0 or $i > 7 or $j < 0 or $j > 7) {
            throw new NoSuchCellException("cell with coordinates $i, $j does not exist");
        }
        return $this->board[$coordinates[0]][$coordinates[1]];
    }

    public function get_neighbour(Cell &$cell, array $shift): ?Cell {
        return $cell->neighbour_exists($shift) ? $this->get_cell($cell->get_neighbour($shift)) : null;
    }

}

<?php

require_once 'ChessPiece.php';


class Rook extends ChessPiece {

    function to_move(): array {
        return [[1, 0], [-1, 0], [0, 1], [0, -1]];
    }

}

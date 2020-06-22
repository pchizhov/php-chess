<?php

require_once 'ChessPiece.php';


class Knight extends ChessPiece {

    function to_move(): array {
        return [[2, 1], [1, 2], [-2, 1], [-1, 2], [2, -1], [1, -2], [-2, -1], [-1, -2]];
    }

}
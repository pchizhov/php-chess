<?php

require_once 'pieces/ChessPiece.php';

class Cell {

    private array $position;
    private ?PieceManager $piece;

    public function __construct($position, PieceManager $piece_manager = null) {
        $this->position = $position;
        $this->piece = $piece_manager;
    }

    public function neighbour_exists(array $shift): bool {
        $i = $this->position[0] + $shift[0];
        $j = $this->position[1] + $shift[1];
        return $i < 8 && $i >= 0 && $j < 8 && $j >= 0;
    }

    public function get_coordinates(): array {
        return $this->position;
    }

    public function &get_piece(): ?PieceManager {
        return $this->piece;
    }

    public function set_piece(PieceManager &$piece_manager): void {
        $this->piece = $piece_manager;
        $this->piece->set_cell($this);
    }

    public function remove_piece(): void {
        $this->piece = null;
    }

    public function is_empty(): bool {
        return $this->piece == null;
    }

    public function get_neighbour(array $shift): array {
        return array($this->position[0] + $shift[0], $this->position[1] + $shift[1]);
    }

    public function to_json() {
        $result = json_decode('{}');
        if ($this->is_empty()) {
            $result->empty = true;
        } else {
            $result->empty = false;
            $result->piece = json_decode('{}');
            $result->piece->piece_type = $this->get_piece()->get_piece_type();
            $result->piece->color = $this->get_piece()->get_color();
        }
        return $result;
    }

}
<?php

require_once 'pieces/PieceEnum.php';
require_once 'pieces/Pawn.php';
require_once 'pieces/King.php';
require_once 'pieces/Rook.php';
require_once 'pieces/Knight.php';
require_once 'pieces/Bishop.php';
require_once 'pieces/Queen.php';
require_once 'board/ChessBoard.php';
require_once 'game/moves/Move.php';
require_once 'game/managers/PawnManager.php';
require_once 'game/managers/ForwardManager.php';
require_once 'game/managers/RookManager.php';
require_once 'game/managers/SimpleManager.php';
require_once 'game/managers/KingManager.php';


class Game {

    private static ChessBoard $board;
    private static array $players;
    private static int $current_player;
    private static ?Cell $selected_cell;
    private static array $moves_history;

    public static function new_game() {
        self::$board = new ChessBoard();
        self::$players = [[], []];
        self::put_pieces(0);
        self::put_pieces(1);
        self::$current_player = 1;
        self::$selected_cell = null;
        self::$moves_history = [];
    }

    public static function create_piece(int $piece_type, int $color, array $pos): PieceManager {
        $cell = self::$board->get_cell($pos);
        $piece = null;
        switch ($piece_type) {
            case PieceEnum::PAWN:
                $piece = new PawnManager(new Pawn($color), $cell);
                break;
            case PieceEnum::ROOK:
                $piece = new RookManager(new Rook($color), $cell);
                break;
            case PieceEnum::KNIGHT:
                $piece = new SimpleManager(new Knight($color), $cell);
                break;
            case PieceEnum::BISHOP:
                $piece = new ForwardManager(new Bishop($color), $cell);
                break;
            case PieceEnum::QUEEN:
                $piece = new ForwardManager(new Queen($color), $cell);
                break;
            case PieceEnum::KING:
                $piece = new KingManager(new King($color), $cell);
                break;
        }
        array_push(self::$players[$color], $piece);
        return $piece;
    }

    public static function put_pieces(int $color): void {
        $row = [
            'pawns' => $color * 5 + 1,
            'major' => $color * 7
        ];
        for ($col = 0; $col < 8; ++$col) {
            self::create_piece(PieceEnum::PAWN, $color, [$row['pawns'], $col]);
        }
        for ($i = 0; $i < 2; ++$i) {
            self::create_piece(PieceEnum::ROOK, $color, [$row['major'], 7 * $i]);
            self::create_piece(PieceEnum::KNIGHT, $color, [$row['major'], 5 * $i + 1]);
            self::create_piece(PieceEnum::BISHOP, $color, [$row['major'], 3 * $i + 2]);
        }
        self::create_piece(PieceEnum::QUEEN, $color, [$row['major'], 3]);
        self::create_piece(PieceEnum::KING, $color, [$row['major'], 4]);
    }

    public static function &get_board(): ChessBoard {
        return self::$board;
    }

    public static function select_cell(array $coordinates): array {
        try {
            self::$selected_cell = self::$board->get_cell($coordinates);
            return !self::$selected_cell->is_empty() &&
                self::$selected_cell->get_piece()->get_color() == self::$current_player ?
                self::$selected_cell->get_piece()->get_moves() : [];
        } catch (NoSuchCellException $e) {
            echo $e;
            return [];
        }
    }

    public static function move(Move &$move) {
        $move->execute();
        self::$selected_cell = null;
        array_push(self::$moves_history, $move);
        self::switch_player();
    }

    public static function one_step_back() {
        array_pop(self::$moves_history)->undo();
        self::switch_player();
    }

    private static function switch_player(): void {
        self::$current_player = self::get_opponent(self::$current_player);
    }

    public static function get_opponent(int $color): int {
        return 1 - self::$current_player;
    }

    public static function check_check(int $color = null): bool {
        foreach (self::$players[isset($color) ? $color : self::$current_player] as $piece) {
            if ($piece->exists()) {
                foreach ($piece->get_moves(false) as $move) {
                    if ($move->threatens_the_king()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function checkmate_check(): bool {
        return self::check_check(self::get_opponent(self::$current_player)) and !self::get_all_moves();
    }

    private static function get_all_moves(): array {
        $moves = [];
        foreach (self::$players[self::$current_player] as $piece) {
            if ($piece->exists()) {
                foreach ($piece->get_moves(false) as $move) {
                    array_push($moves, $move);
                }
            }
        }
        return $moves;
    }

}
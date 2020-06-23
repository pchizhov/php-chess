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

    public static function new_game() {
        self::$board = new ChessBoard();
        self::$players = [[], []];
        self::put_pieces(0);
        self::put_pieces(1);
        self::$current_player = 1;
        self::save();
    }

    public static function game_from_file($filename = 'game.json') {
        self::$players = [[], []];
        $file_content = json_decode(file_get_contents($filename));
        self::$board = new ChessBoard();
        for ($i = 0; $i < 8; ++$i) {
            for ($j = 0; $j < 8; ++$j) {
                $cell = $file_content->board[$i][$j];
                if (!$cell->empty) {
                    self::create_piece($cell->piece->piece_type, $cell->piece->color, [$i, $j]);
                }
            }
        }
        self::$current_player = $file_content->current_player;
    }

    public static function save() {
        $result = json_decode('{}');
        $result->board = [[], [], [], [], [], [], [], []];
        for ($i = 0; $i < 8; ++$i) {
            for ($j = 0; $j < 8; ++$j) {
                $cell = self::$board->get_cell([$i, $j])->to_json();
                array_push($result->board[$i], $cell);
            }
        }
        $result->current_player = self::$current_player;
        $file = fopen('game.json', 'w');
        fwrite($file, json_encode($result));
        fclose($file);
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

    public static function move_from_json($json_move): Move {
        switch ($json_move->type) {
            case MoveEnum::CASTLING:
                $move = new Castling(
                    Game::get_board()->get_cell($json_move->rook_from),
                    Game::get_board()->get_cell($json_move->rook_to),
                    Game::get_board()->get_cell($json_move->king_from),
                    Game::get_board()->get_cell($json_move->king_to)
                );
                break;
            case MoveEnum::PAWN_PROMOTION:
                $move = new PawnPromotion(
                    Game::get_board()->get_cell($json_move->from),
                    Game::get_board()->get_cell($json_move->to),
                    $json_move->new_piece
                );
                break;
            default:
                $move = new SimpleMove(
                    Game::get_board()->get_cell($json_move->from),
                    Game::get_board()->get_cell($json_move->to)
                );
        }
        return $move;
    }

    public static function &get_board(): ChessBoard {
        return self::$board;
    }

    public static function select_cell(array $coordinates): array {
        $selected_cell = self::$board->get_cell($coordinates);
        return !$selected_cell->is_empty() &&
            $selected_cell->get_piece()->get_color() == self::$current_player ?
            $selected_cell->get_piece()->get_moves() : [];
    }

    public static function move(Move &$move) {
        $move->execute();
        self::switch_player();
    }

    private static function switch_player(): void {
        self::$current_player = self::get_opponent(self::$current_player);
    }

    public static function get_opponent(int $color): int {
        return 1 - $color;
    }

    public static function under_check(int $color = null): bool {
        foreach (self::$players[self::get_opponent(isset($color) ? $color : self::$current_player)] as $piece) {
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

    public static function under_checkmate($color = null): bool {
        return self::under_check($color) and !self::get_all_moves($color);
    }

    private static function get_all_moves($color = null): array {
        $moves = [];
        foreach (self::$players[isset($color) ? $color : self::$current_player] as $piece) {
            if ($piece->exists()) {
                foreach ($piece->get_moves(false) as $move) {
                    array_push($moves, $move);
                }
            }
        }
        return $moves;
    }

}
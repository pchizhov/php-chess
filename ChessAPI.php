<?php

require_once 'game/Game.php';

class ChessAPI {

    public static function new_game() {
        Game::new_game();
        Game::save();
        return self::get_board();
    }

    public static function get_board() {
        $file = 'game.json';
        $file_content = json_decode(file_get_contents($file));
        return $file_content->board;
    }

    public static function get_current_player() {
        $file = 'game.json';
        $file_content = json_decode(file_get_contents($file));
        return $file_content->current_player;
    }

    public static function select_cell(array $position) {
        if (!is_array($position) or count($position) != 2) {
            throw new UnexpectedValueException("Position should be an array of 2 elements");
        }
        Game::game_from_file();
        $moves = Game::select_cell($position);
        $result = json_decode('{"moves": []}');
        foreach ($moves as $move) {
            array_push($result->moves, $move->to_json());
        }
        return $result;
    }

    public static function execute_move($json_move) {
        Game::game_from_file();
        $move = Game::move_from_json($json_move);
        Game::move($move);
        Game::save();
    }

    public static function under_check($color) {
        if (!isset($color) or $color != 1 and $color != 0 or !is_int($color)) {
            throw new UnexpectedValueException("Color should be 0 or 1, $color given");
        }
        Game::game_from_file();
        return Game::under_check($color);
    }

    public static function under_checkmate($color) {
        if (!isset($color) or $color != 1 and $color != 0 or !is_int($color)) {
            throw new UnexpectedValueException("Color should be 0 or 1, $color given");
        }
        Game::game_from_file();
        return Game::under_checkmate($color);
    }

}
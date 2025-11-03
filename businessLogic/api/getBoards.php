<?php
require __DIR__ . '/../services/BoardService.php';

$boardService = new BoardService();

foreach($boardService->getBoards() as $board) {
    echo $board->getBoard() . "<br>";
}
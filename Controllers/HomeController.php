<?php

namespace Controllers;

use DAO\GenreDAO;
use DAO\MovieDAO as MovieDAO;
use DAO\MovieShowDAO as MovieShowDAO;
use DAO\CinemaDAOMSQL as CinemaDAOMSQL;
use DAO\RoomDAOMSQL as RoomDAO;
use DAO\TypeMovieShowDAO as TypeMovieShowDAO;
use DAO\BillBoardDAO as BillBoardDAO;
use Models\MovieShowDTO as MovieShowDTO;
use DAO\MovieDAOMSQL as MovieDAOMSQL;

class HomeController
{
    private $movieDAO;
    private $movieDAOMSQL;
    private $genreDAO;
    private $movieShowDAO;
    private $cinemaDAO;
    private $roomDAO;
    private $typeMovieShowDAO;
    private $billBoardDAO;

    public function __construct()
    {
        $this->movieDAO = new MovieDAO();
        $this->movieDAOMSQL = new MovieDAOMSQL();
        $this->genreDAO = new GenreDAO();
        $this->movieShowDAO = new MovieShowDAO();
        $this->cinemaDAO = new CinemaDAOMSQL();
        $this->movieDAO = new MovieDAO();
        $this->roomDAO = new RoomDAO();
        $this->typeMovieShowDAO = new TypeMovieShowDAO();
        $this->billBoardDAO = new BillBoardDAO();
    }

    // se llaman a las vistas de home.php.
    public function index($message = "")
    {
        $movieShows = $this->getMovieShowList();// trae todas las funciones disponibles.
        $this->movieDAOMSQL->updateFromApi();
        if (empty($movieShows)){
            //Por hacer:
            //return require_once(VIEWS_PATH."error_404.php");  
            $message = "E R R O R, No existen funciones pendientes.";
        }
        require_once(VIEWS_PATH . "home.php");
    }

    //trae Todas las movieShow y la almacena en un un array de movieShowDTO.
    private function getMovieShowList()
    {
        $movieShows = $this->movieShowDAO->getAll();
        $listMovieShow = array();
        foreach ($movieShows as $movieShow) {
            $movieShowDTO = new MovieShowDTO();
            $movieShowDTO->setId($movieShow->getId());
            $movieShowDTO->setDate($movieShow->getDate());
            $movieShowDTO->setTime($movieShow->getTime());
            $movieShowDTO->setMovie($this->movieDAO->get($movieShow->getMovie()));
            $billBoard = $this->billBoardDAO->get($movieShow->getBillBoard());
            //$cinema = $this->cinemaDAO->get($billBoard->getIdCinema());
            //$movieShowDTO->setNameCinema($cinema->getName());
            $room = $this->roomDAO->get($movieShow->getRoom());
            /*$movieShowDTO->setRoomName($room->getName());*/
            $movieShowDTO->setTypeMovieShow($this->typeMovieShowDAO->getName($movieShow->getTypeMovieShow()));

            array_push($listMovieShow, $movieShowDTO);
        }
        return $listMovieShow;
    }
}

<?php

namespace DAO;

use Exception;
use Models\Cinema;
use Models\Genre;
use Models\Movie;
use Models\MovieShow;
use Models\MovieShowDTO;
use Models\RoomDTO;
use Models\TypeMovieShow;
use Models\TypeRoom;

class MovieShowDAOMSQL implements IMovieShowDAO
{
    private $nameTable = "movieshows";
    private $conection;

    public function add(MovieShow $newMovieShow)
    {
        try {
            $sql = "INSERT INTO " . $this->nameTable . " (idmovie , idcinema , idtypemovieshow , idroom , date_ , time_ , isactiveMovieShow )
            VALUES (:idmovie , :idcinema , :idtypemovieshow , :idroom , :date_ , :time_ , :isactiveMovieShow)";

            $parameters['idmovie'] = $newMovieShow->getMovie();
            $parameters['idcinema'] = $newMovieShow->getCinema();
            $parameters['idtypemovieshow'] = $newMovieShow->getTypeMovieShow();
            $parameters['idroom'] = $newMovieShow->getRoom();
            $parameters['date_'] = $newMovieShow->getDate();
            $parameters['time_'] = $newMovieShow->getTime();
            $parameters['isactiveMovieShow'] = $newMovieShow->getIsActive();
            $this->conection = Connection::getInstance();
            $this->conection->ExecuteNonQuery($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getAll()
    {
        $listMovieShow = array();
        try {
            $sql = "call get_movieshows()";

            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        var_dump($result);
        if (!empty($result)) {
            foreach ($result as $movieShow) {
                $newMovieShow = $this->creatMovieShow($movieShow);
                array_push($listMovieShow, $newMovieShow);
            }
        }
        return $listMovieShow;
    }

    public function getAllActive()
    {
        $listMovieShow = array();
        try {
            $sql = "call get_movieshows_active();";

            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($result)) {
            foreach ($result as $movieShow) {
                $newMovieShow = $this->creatMovieShow($movieShow);
                array_push($listMovieShow, $newMovieShow);
            }
        }
        return $listMovieShow;
    }

    public function getMovieShowBycinema($id)
    {
        try {
            $sql = "SELECT * FROM " . $this->nameTable . " as m 
            INNER JOIN typemovieshows as tm 
            ON m.idtypemovieshow = tm.idtypemovieshow
            INNER JOIN movies as mo
            ON m.idmovie = mo.idmovie 
            INNER JOIN rooms as r 
            ON m.idroom = r.idroom 
            INNER JOIN typerooms as t 
            ON r.idtyperoom = t.idtyperoom 
            WHERE m.idcinema = :idcinema";
            $parameters['idcinema'] = $id;
            $listMovieShow = array();
            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($result)) {
            foreach ($result as $movieShow) {

                $newMovieShow = mapperDAO :: creatMovieShow($movieShow);
                array_push($listMovieShow, $newMovieShow);
            }
        }
        return $listMovieShow;
    }


    public function remove($id)
    {
    }
    public function get($id)
    {
        $newMovieShow = null;
        try {
            $sql = "SELECT * FROM " . $this->nameTable . " as m 
            INNER JOIN typemovieshows as tm 
            ON m.idtypemovieshow = tm.idtypemovieshow
            INNER JOIN movies as mo
            ON m.idmovie = mo.idmovie 
            INNER JOIN rooms as r 
            ON m.idroom = r.idroom 
            INNER JOIN typerooms as t 
            ON r.idtyperoom = t.idtyperoom
            INNER JOIN cinemas as c 
            ON m.idcinema = c.idcinema 
            WHERE m.idmovieshow = :id";
            $parameters['id'] = $id;

            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($result)) {
            foreach ($result as $movieShow) {
                $newMovieShow = $this->creatMovieShow($movieShow);
            }
        }
        return $newMovieShow;
    }


    public function getMovieShowByMovie($idMovie)
    {
        $listMovieShow = array();
        try {

            $sql = "SELECT * FROM " . $this->nameTable . " as m 
            INNER JOIN typemovieshows as tm 
            ON m.idtypemovieshow = tm.idtypemovieshow
            INNER JOIN movies as mo
            ON m.idmovie = mo.idmovie 
            INNER JOIN rooms as r 
            ON m.idroom = r.idroom 
            INNER JOIN typerooms as t 
            ON r.idtyperoom = t.idtyperoom 
            INNER JOIN cinemas as c 
            ON m.idcinema = c.idcinema 
            WHERE mo.idmovie = :idmovie";
            $parameters['idmovie'] = $idMovie;
            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($result)) {
            foreach ($result as $movieShow) {
                $newMovieShow = $this->creatMovieShow($movieShow);
                array_push($listMovieShow, $newMovieShow);
            }
        }
        return $listMovieShow;
    }

    public function getMovieShowByMovieDate($idMovie, $date)
    {
        $listMovieShow = array();
        try {

            $sql = "SELECT * FROM " . $this->nameTable . " as m 
            INNER JOIN typemovieshows as tm 
            ON m.idtypemovieshow = tm.idtypemovieshow
            INNER JOIN movies as mo
            ON m.idmovie = mo.idmovie 
            INNER JOIN rooms as r 
            ON m.idroom = r.idroom 
            INNER JOIN typerooms as t 
            ON r.idtyperoom = t.idtyperoom 
            INNER JOIN cinemas as c 
            ON m.idcinema = c.idcinema 
            WHERE m.idcinema = :idcinema AND m.date_ = :date";
            $parameters['idcinema'] = $idMovie;
            $parameters['date'] = $date;
            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($result)) {
            foreach ($result as $movieShow) {
                $newMovieShow = $this->creatMovieShow($movieShow);
                array_push($listMovieShow, $newMovieShow);
            }
        }
        return $listMovieShow;
    }

    public function getMovieShowByMovieDateCinema($idMovie, $date, $idCinema)
    {
        $listMovieShow = array();
        try {
            $sql = "SELECT * FROM " . $this->nameTable . " as m 
            INNER JOIN typemovieshows as tm 
            ON m.idtypemovieshow = tm.idtypemovieshow
            INNER JOIN movies as mo
            ON m.idmovie = mo.idmovie 
            INNER JOIN rooms as r 
            ON m.idroom = r.idroom 
            INNER JOIN typerooms as t 
            ON r.idtyperoom = t.idtyperoom 
            INNER JOIN cinemas as c 
            ON m.idcinema = c.idcinema 
            WHERE m.idcinema = :idcinema AND m.date_ = :date AND m.idmovie = :idmovie";
            $parameters['idmovie'] = $idMovie;
            $parameters['idcinema'] = $idCinema;
            $parameters['date'] = $date;
            $this->conection = Connection::getInstance();
            $result = $this->conection->Execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($result)) {
            foreach ($result as $movieShow) {
                $newMovieShow = $this->creatMovieShow($movieShow);
                array_push($listMovieShow, $newMovieShow);
            }
        }
        return $listMovieShow;
    }

    protected function creatMovieShow($value)
    {
        $value = ($value) ? $value : array();
        if (!empty($value)) {
            $newMovieShow = new MovieShowDTO();
            $newMovieShow->setId($value['idmovieshow']);
            $newMovieShow->setMovie($this->mapearMovie($value));
            $newMovieShow->setRoom($this->mapearRoom($value));
            $newMovieShow->setTypeMovieShow($this->mapearTypeMovieShow($value));
            $newMovieShow->setCinema($this->mapearCinema($value));
            $newMovieShow->setDate($value['date_']);
            //$time = $value['time_'];
            $time = strtotime($value['time_']);
            $timeNow = date('H:i' , $time);
            $newMovieShow->setTime($timeNow);
            return $newMovieShow;
        }
    }


    protected function mapearRoom($value)
    {
        $value = ($value) ? $value : array();
        if (!empty($value)) {
            $newRoom = new RoomDTO();
            $newRoom->setId($value['idroom']);
            $newRoom->setName($value['nameroom']);
            $newRoom->setCapacity($value['capacity']);
            $newRoom->setTypeRoom($this->mapearTypeRoom($value));
            $newRoom->setIsActive($value['isactiveMovieShow']);
            $newRoom->setTicketCost($value['ticketcost']);
            return $newRoom;
        }
    }

    protected function mapearTypeRoom($value)
    {
        $value = ($value) ? $value : array();
        if (!empty($value)) {
            $newTypeRoom = new TypeRoom();
            $newTypeRoom->setId($value['idtyperoom']);
            $newTypeRoom->setName($value['nametyperoom']);

            return $newTypeRoom;
        }
    }

    protected function mapearTypeMovieShow($value)
    {
        $value = ($value) ? $value : array();
        if (!empty($value)) {
            $newTypeRoom = new TypeMovieShow();
            $newTypeRoom->setId($value['idtypemovieshow']);
            $newTypeRoom->setName($value['nametypemovieshow']);

            return $newTypeRoom;
        }
    }

    protected function mapearCinema($value)
    {
        $value = ($value) ? $value : array();
        if (!empty($value)) {
            $newCinema = new Cinema();
            $newCinema->setName($value['namecinema']);
            $newCinema->setAdress($value['adress']);
            $newCinema->setPhonenumber($value['phonenumber']);

            return $newCinema;
        }
    }

    protected function mapearMovie($value)
    {
        $value = ($value) ? $value : array();
        if (!empty($value)) {
            $movie = new Movie();
            $movie->setId($value["idmovie"]);

            $movie->setImdbID($value["imdbid"]);
            $movie->setName($value["namemovie"]);
            $movie->setSynopsis($value["synopsis"]);
            $movie->setPoster($value["poster"]);
            $movie->setBackground($value["background"]);
            $movie->setVoteAverage($value["voteAverage"]);
            $movie->setRunTime($value["runtime"]);
            $movie->setGenreId($this->getGenresById($movie->getId()));
            return $movie;
        }
    }

    public function getGenresById($idmovie)
    {
        $listGenres = array();
        $query = " SELECT * FROM genresxmovie as gxm INNER JOIN genres as g ON gxm.idgenre = g.idgenre WHERE gxm.idmovie = :idmovie";
        $parameters["idmovie"] = $idmovie;

        $this->connection = Connection::getInstance();
        $result = $this->connection->execute($query, $parameters);
        foreach ($result as $genres) {
            $newGenre = new Genre();
            $newGenre->setId($genres['idgenre']);
            $newGenre->setName($genres['namegenre']);
            array_push($listGenres, $newGenre);
        }
        return $listGenres;
    }
}

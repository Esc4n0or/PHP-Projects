

<?php

	// Function to get all items

	function getAll($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

		global $con;

		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

		$getAll->execute();

		$all = $getAll->fetchAll();

		return $all;

	}
    // Redirect Function

    function redirectBack($Message, $url = null, $seconds = 1) {

        if ($url === null) {

            $url    = "index.php";
            $link   = "Home Page";
            
        } else {
            
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !='') {

                $url = $_SERVER['HTTP_REFERER'];
                $link   = "Previous Page";

            } else {

                $url = "index.php";
                $link   = "Home Page";
            }
        }

        echo $Message;

        echo "<div class='alert alert-info'>You will be redirected to $link after $seconds seconds</div>";

        header("refresh:$seconds;url=$url");

        exit();

    }


    /*
	** Check Items Function v1.0
	** Function to Check Item In Database [ Function Accept Parameters ]
	** $select = The Item To Select [ Example: user, item, category ]
	** $from = The Table To Select From [ Example: users, items, categories ]
	** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
	*/

	function checkExist($select, $from, $value) {

		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statement->execute(array($value));

		$count = $statement->rowCount();

		return $count;

	}

    /*
	** Count Number Of Items Function v1.0
	** Function To Count Number Of Items Rows
	** $item = The Item To Count
	** $table = The Table To Choose From
	*/

	function countItems($item, $table) {

		global $con;

		$statement2 = $con->prepare("SELECT COUNT($item) FROM $table");

		$statement2->execute();

		return $statement2->fetchColumn();

	}


	/*
	** Get Latest Records Function v1.0
	** Function To Get Latest Items From Database [ Users, Items, Comments ]
	** $select = Field To Select
	** $table = The Table To Choose From
	** $order = The Desc Ordering
	** $limit = Number Of Records To Get
	*/

	function getLatest($select, $table, $order, $limit = 5) {

		global $con;

		$statement3 = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$statement3->execute();

		$rows = $statement3->fetchAll();

		return $rows;

	}
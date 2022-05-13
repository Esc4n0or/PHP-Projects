

<?php

	/*
	** Get All Records Function v1.0
	** Function To Get All Items From Database [ Users, Items, Comments ]
	** $tableName = Table to select from
	*/

	function getAll($tableName, $order, $where = NULL) {

		global $con;

		$sql =  $where == NULL ? '' : $where;

		$statement3 = $con->prepare("SELECT * FROM $tableName $sql ORDER BY $order DESC");

		$statement3->execute();

		$all = $statement3->fetchAll();

		return $all;

	}
	/*
	** Get Latest Records Function v1.0
	** Function To Get Latest Items From Database [ Users, Items, Comments ]
	** $select = Field To Select
	** $table = The Table To Choose From
	** $order = The Desc Ordering
	** $limit = Number Of Records To Get
	*/

	function getCat() {

		global $con;

		$statement3 = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

		$statement3->execute();

		$rows = $statement3->fetchAll();

		return $rows;

	}

	function getItem($where, $value) {

		global $con;


		$statement3 = $con->prepare("SELECT * FROM items WHERE $where = ? ORDER BY item_id DESC");

		$statement3->execute(array($value));

		$items = $statement3->fetchAll();

		return $items;

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

	function checkUserStatus($user) {
		global $con;

		$statement4 = $con->prepare("SELECT username, reg_status FROM users WHERE username = ? AND reg_status = 0");
		$statement4->execute(array($user));
		$status = $statement4->rowCount();

		return $status;
	}
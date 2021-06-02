<?php 

class Manage 
{
    private $con;
    function __construct()
    {
        include_once("../database/db.php");
        $db = new database();
        $this->con =  $db->connect();
    }
   public function manageRecordWithPagination($table){
		//  $a = $this->pagination($this->con,$table,$pno,5);
		if ($table == "catagories") {
			$sql = "SELECT p.cid,p.catagory_name as category, c.catagory_name as parent, p.status FROM catagories p LEFT JOIN catagories c ON p.parent_cat=c.cid ";
		} 
		
		else if($table == "products"){
			$sql = "SELECT p.pid,p.product_name,c.catagory_name,b.brand_name,p.product_price,p.product_stock,p.added_date,p.p_status FROM products p,brands b,catagories c WHERE p.bid = b.bid AND p.cid = c.cid ";


		}
		 else {
			$sql = "SELECT * FROM " . $table ;
		}
		$result = $this->con->query($sql) or die($this->con->error);
		$rows = array();
		if($result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		// return  ["rows"=>$rows,"pagination"=>$a["pagination"]];
		return ["rows" =>$rows];

	}

	// private function pagination($con,$table,$pno,$n){
	// 	$query = $con->query("SELECT COUNT(*) as rows FROM ".$table);
	// 	$row = mysqli_fetch_assoc($query);
	// 	//$totalRecords = 100000;
	// 	$pageno = $pno;
	// 	$numberOfRecordsPerPage = $n;

	// 	$last = ceil($row["rows"]/$numberOfRecordsPerPage);

	// 	$pagination = "<ul class='pagination'>";

	// 	if ($last != 1) {
	// 		if ($pageno > 1) {
	// 			$previous = "";
	// 			$previous = $pageno - 1;
	// 			$pagination .= "<li class='page-item'><a class='page-link' pn='".$previous."' href='#' style='color:#333;'> Previous </a></li>";
	// 		}
	// 		for($i=$pageno - 5;$i< $pageno ;$i++){
	// 			if ($i > 0) {
	// 				$pagination .= "<li class='page-item'><a class='page-link' pn='".$i."' href='.$i'> ".$i." </a></li>";
	// 			}
				
	// 		}
	// 		$pagination .= "<li class='page-item'><a class='page-link' pn='".$pageno."' href='.$pageno' style='color:#333;'> $pageno </a></li>";
	// 		for ($i=$pageno + 1; $i <= $last; $i++) { 
	// 			$pagination .= "<li class='page-item'><a class='page-link' pn='".$i."' href='#'> ".$i." </a></li>";
	// 			if ($i > $pageno + 4) {
	// 				break;
	// 			}
	// 		}
	// 		if ($last > $pageno) {
	// 			$next = $pageno + 1;
	// 			$pagination .= "<li class='page-item'><a class='page-link' pn='" . $pageno . "' href='.$pageno' style='color:#333;'> $pageno </a></li> </ul>";

				
				
	// 		}
	// 	}
	// //LIMIT 0,10
	// 	//LIMIT 20,10
	// 	$limit = "LIMIT ".($pageno - 1) * $numberOfRecordsPerPage.",".$numberOfRecordsPerPage;

	// 	return ["pagination"=>$pagination,"limit"=>$limit];
	// }

	public function deleteRecord($table ,$pk, $id)
	{
		if($table == "catagories")
		{
			$pre_stmt = $this->con->prepare("SELECT ".$id." FROM catagories WHERE parent_cat= ?");
			$pre_stmt->bind_param("i",$id);
			$pre_stmt->execute();
			$result = $pre_stmt->get_result() or die($this->con->Error);
			if($result->num_rows >0)
			{
				return "Dependent";
			}
			else {
				$pre_stmt = $this->con->prepare("DELETE FROM " . $table . " WHERE " . $pk . " = ?");
				$pre_stmt->bind_param("i", $id);
				$result = $pre_stmt->execute() or die($this->con->error);
				if($result)
				{
					return "Category_Deleted";
				}

			
			}
		}
		else {
			$pre_stmt = $this->con->prepare("DELETE FROM ".$table." WHERE ".$pk." = ?");
			$pre_stmt->bind_param("i",$id);
			$result = $pre_stmt->execute() or die($this->con->error);
				if ($result) {
					return "DELETED";
			}
		}
	}


	public function getSingleRecord($table ,$pk,$id)
	{
		$pre_stmt = $this->con->prepare("SELECT * FROM ".$table." WHERE ".$pk."= ? LIMIT 1");
		$pre_stmt->bind_param("i",$id);
		$pre_stmt->execute() or die($this->con->Error);
		$result = $pre_stmt->get_result();
		if($result->num_rows ==1)
		{
			$row = $result->fetch_assoc();
		}
		return $row;

	}

	// update records
	public function update_record($table, $where, $fields)
	{
		$sql = "";
		$condition = "";
		foreach ($where as $key => $value) {
			// id = '5' AND m_name = 'something'
			$condition .= $key . "='" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		foreach ($fields as $key => $value) {
			//UPDATE table SET m_name = '' , qty = '' WHERE id = '';
			$sql .= $key . "='" . $value . "', ";
		}
		$sql = substr($sql, 0, -2);
		$sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;
		if (mysqli_query($this->con, $sql)) {
			return "UPDATED";
		}
	}

	public function storeCustomerOrderInvoice($orderdate,$cust_name,$ar_tqty,$ar_qty,$ar_price,$ar_pro_name,$sub_total,$gst,$discount,$net_total,$paid,$due,$payment_type)
	{
		// $date  = date("Y-m-d")
		$pre_stmt = $this->con->prepare("INSERT INTO `invoice`( `customer_name`, `order_date`, `sub_total`, `gst`, `discount`, `net_total`, `paid`, `due`, `payment_type`) VALUES (?,?,?,?,?,?,?,?,?)");
		$pre_stmt->bind_param("ssdddddds", $cust_name,$orderdate,$sub_total, $gst,$discount,$net_total,$paid,$due,$payment_type);
		$pre_stmt->execute() or die($this->con->Error);
		
		$invoice_no = $pre_stmt->insert_id;
		if($invoice_no != null)
		{
			for($i=0; $i<count($ar_qty); $i++)
			{
				$rem_stock = $ar_tqty[$i] - $ar_qty[$i];

				if($rem_stock <= 0)
				{
					return "Order failed due to Insufficient stock";

				}
				else {
					$this->con->query("UPDATE  products SET product_stock = '$rem_stock' WHERE product_name = '".$ar_pro_name[$i]."'");
				}
				$insert_product = $this->con->prepare("INSERT INTO `invoice_details`( `invoice_no`, `product_name`, `price`, `qty`) VALUES (?,?,?,?)");
				$insert_product->bind_param("isdd",$invoice_no,$ar_pro_name[$i],$ar_price[$i],$ar_qty[$i]);
				$insert_product->execute() or die ($this->con->error);
			}

			return $invoice_no;
		}
		
	}

  
}
 //$obj = new Manage();
//  print_r($obj->getSingleRecord("catagories","cid",1));
//  echo $obj->deleteRecord("Brands","bid",12);
// echo "<pre>";
// print_r($obj->manageRecordWithPagination("catagories",2));
//  echo $obj->update_record("catagories",["cid"=>1],["parent_cat"=>0,"catagory_name"=>"Electron","status"=>1]);
?>
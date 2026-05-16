<?php
include 'db_connect.php';
$order_id = (int)$_GET['id'];
$order_meta = $conn->query("SELECT status FROM orders WHERE id = ".$order_id)->fetch_assoc();
$order_confirmed = $order_meta && (int)$order_meta['status'] === 1;
?>
<div class="container-fluid">
	
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Qty</th>
				<th>Order</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$total = 0;
			$qry = $conn->query("SELECT * FROM order_list o inner join product_list p on o.product_id = p.id where order_id = ".$order_id);
			while($row=$qry->fetch_assoc()):
				$total += $row['qty'] * $row['price'];
			?>
			<tr>
				<td><?php echo $row['qty'] ?></td>
				<td><?php echo $row['name'] ?></td>
				<td><?php echo number_format($row['qty'] * $row['price'],2) ?></td>
			</tr>
		<?php endwhile; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2" class="text-right">TOTAL</th>
				<th ><?php echo number_format($total,2) ?></th>
			</tr>

		</tfoot>
	</table>
	<div class="text-center">
		<?php if(!$order_confirmed): ?>
		<button class="btn btn-primary" id="confirm" type="button" onclick="confirm_order()">Confirm order</button>
		<?php else: ?>
		<span class="badge badge-success mb-2 d-inline-block">Already confirmed</span><br>
		<?php endif; ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

	</div>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
</style>
<script>
	function confirm_order(){
		start_load()
		$.ajax({
			url:'ajax.php?action=confirm_order',
			method:'POST',
			data:{id:'<?php echo $_GET['id'] ?>'},
			success:function(resp){
				if(resp == 1){
					alert_toast("Order confirmed.")
                        setTimeout(function(){
                            location.reload()
                        },1500)
				}
			}
		})
	}
</script>
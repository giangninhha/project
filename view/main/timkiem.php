<?php
			$sx='';
			if(isset($_POST['sx'])){
				$sx=$_POST['sx'];
			}
			switch ($sx) {
					case 'price-ascending':
						$sx1=" ORDER BY DonGia ASC ";
						break;
					case 'price-descending':
						$sx1=" ORDER BY DonGia DESC ";
						break;
					default:
						$sx1=" ";
						break;
				
			}
            $tukhoa=$_POST['search'];
            // lấy sản phẩm cần tìm
            $ds_spnb="SELECT * FROM `sanpham` WHERE `TenSP`  LIKE N'%".$tukhoa."%' ".$sx1;
            $query_dssp=mysqli_query($conn,$ds_spnb);
            $sosp=mysqli_num_rows($query_dssp);

?>
<div class="container-fluid ">
	<div class="container">
		<div class="row boderbot" >
			<h3 class="col-md-10 ft"> <?php echo 'Kết quả tìm kiếm : " '.$tukhoa.' "'; ?> </h3>
			<select name="SortBy" id="SortBy" onchange="validateSelectBox(this)" class="form-control form-control-sm col-2 form-horizontal">
			    <option value="default">Tùy chọn</option>
			    <option <?php if ($sx=='price-ascending') {echo 'selected';}?>
			     value="price-ascending">Giá từ thấp tới cao</option>
			    <option <?php if ($sx==='price-descending') {echo ' selected="selected"';}?>
			     value="price-descending">Giá từ cao tới thấp</option>
			</select>
		</div>

		<div class="row boderbot ">
			<?php 
            if($sosp>0){ while ($kq_dssp=mysqli_fetch_array($query_dssp)) {

	             // lấy tên nhà sản xuất sản phẩm nổi bật
	            $ncc="SELECT * FROM `nhacc` WHERE `MaNCC`=".$kq_dssp['MaNCC'];
	            $query_ncc=mysqli_query($conn,$ncc);
	            $kq_ncc=mysqli_fetch_array($query_ncc);
	             $km="SELECT * FROM `sanphamkhuyenmai` WHERE `MaSP`=".$kq_dssp['MaSP'];
	            $query_km=mysqli_query($conn,$km);
	            date_default_timezone_set('Asia/Ho_Chi_Minh');$date=getdate();
	            $ngay=$date['year']."-".$date['mon']."-".($date['mday']);$a=0;$b=0;
	             while ($kq_km=mysqli_fetch_array($query_km)) {
			        $km1="SELECT * FROM `khuyenmai` WHERE `MaKM`=".$kq_km['MaKM']." and NgayBD <='".$ngay."' and NgayKT >='".$ngay."'";
		            $query_km1=mysqli_query($conn,$km1);
		            while ($kq_km=mysqli_fetch_array($query_km1)) { 
		            		 if(isset($kq_km['KM_PT'])){ $b=$b+($kq_km['KM_PT']);} 
		            		 if(isset($kq_km['TienKM'])){ $a=$a+($kq_km['TienKM']);} 				            	
					}	
				} 
	         ?>
	         <div class="col-3 item">
	         	<div class="item2">
	         		<a  href="index.php?view=chitietsanpham&masp=<?php echo $kq_dssp['MaSP']; ?>" >
						<div><img src="./webroot/img/sanpham/<?php echo $kq_dssp['AnhNen'] ; ?>"  class="img-circle img-thumbnail item-img">
								<?php   if ($a!==0){
									if($b!==0){echo '<label class="sale">Giảm: '.$b.'% & '.number_format($a).'đ</label>';}
									else{echo '<label class="sale1">Giảm: '.number_format($a).'đ</label>';}
								}else if ($b!==0){echo '<label class="sale2">Giảm: '.$b.'%</label>';}?>
						</div>
						<div class="item-text ft text-center">
							<h6 ><?php echo $kq_dssp['TenSP']?></h6> 

				         	<?php   	if ($a!==0 || $b!==0) {?>
							<p class="ft2 gia"><strike><?php echo number_format($kq_dssp['DonGia'], 0 , $c = "," , $d = "." ).' '.' VND' ; ?></strike></p>
			<?php $t=0; if($b==0){$t=$kq_dssp['DonGia']-$a;}else{$t=($kq_dssp['DonGia']-($kq_dssp['DonGia']*$b)/100)-$a;}?>
							<p class="ft2 gia2"><mark class="badge-danger"><?php echo number_format($t, 0 , $c = "," , $d = "." ).' '.' VND' ; ?></mark></p>
				  <?php }else{?><p class="ft2 "><mark class="badge-danger"><?php echo number_format($kq_dssp['DonGia'], 0 , $c = "," , $d = "." ).' '.' VND' ; ?></mark></p> 
				  <?php }?>
				         	<p ><?php echo ($kq_ncc['TenNCC']) ; ?></p>
						</div>	 
					</a>
	         	</div>
	         </div>
		<?php }  }else{ 
        echo "<h3  style='margin-top:100px;margin-bottom:100px;margin-left:320px; '>Không tìm thấy sản phẩm nào </h3>";
       }?>
		</div>
	</div>
</div>
</div>  
<!-- sắp xếp sản phẩm  -->
		<form action="?view=timkiem" method="POST" accept-charset="utf-8">
			<input hidden name="search" value="<?php echo $tukhoa ?>">
			<input hidden name="btsearch" >
			 <input type="submit" name="sx" id="result" hidden>
		</form>    
        <script language="javascript">
            function validateSelectBox(obj){
                var options = obj.children;
                var html = '';
                for (var i = 0; i < options.length; i++){
                    if (options[i].selected){
                       document.getElementById('result').value =options[i].value;
                       jQuery('#result').click();
                    }
                }      
               
            }
        </script>
 <!-- end sắp xếp sản phẩm  -->
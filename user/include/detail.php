<div class="xxxx" style="width:1000px; height:30px"></div>
<div class="detail">
	<?php
	include("admin/Entity/crud_film.php");
	$film_d = new film_director();
	$director =new director();
	$actor = new actor();
	$film_a = new film_actor();
		$id_f=$_GET['id_film'];
        $sql="select * from film where id_film=:id_film";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":id_film",$id_f);
        $stmt->execute();
        $r=$stmt->fetch(PDO::FETCH_BOTH);
	?>
    <div class="detail-l" style="width:670px">
    	 <div class="dt-box">
                <img STYLE="width:300px;height:350px" src="image/film/<?php echo $r['img'];?>" />
			 <ul class="as">
				 <li style="float: left">
					 <a href='index.php?ac=xem&id=<?php echo $id_f ?>'>
						 <input type='submit' class='xem' value='Xem Phim' name="view_movie" /></a>
				 </li>
				 <li style="float: left">
					 <a href='index.php?ac=trailer1&id=<?php echo $id_f ?>'>
						 <input type='button' class='xem' value='Trailer'/></a>
				 </li>
			 </ul>
         </div>
           <div class="gt-f-t">
		<h3 style="font-size:22px"><br>	<?php echo $r['film_name']; ?></h3></div>
            <div class="gt-f-b">
         <div class="gt-film">

            	<div class="gt-f-b-l">
				<p>
					<span style="color:#69F" style="margin-left: 10px">Đạo Diễn</span> :
 <?php

 if(($film_d->getByAll_FD($_REQUEST['id_film']))!=null) {
	 foreach ($film_d->getByAll_FD($_REQUEST['id_film']) as $values) {
		 extract($director->getById_D($values['id_director']));
		 echo $director_name.', ';
	 }
 }
 else
 {
	 echo 'Đang cập nhật';
 }
 //extract($director->getById_D($id_director));
 //echo $director_name;
?>
					</p><br />
					<p><span style="color:#69F;float:left"> Thể Loại :</span>
						<?php
						$sql = "select type.id_type, type.type_name from type, film_type
    where type.id_type = film_type.id_type
    and id_film='" .$r['id_film']."' ";
						$temp = $pdo->query($sql);
						$dataTL = $temp->fetchAll(PDO::FETCH_ASSOC);
						//	print_r($dataTL);

						foreach($dataTL as $rtl)
						{
							?>
							<span style="float:left">
            <a href="index.php?ac=kqtlf&id_type=<?php echo $rtl['id_type'] ?>" style="text-decoration:none;float:left;color:#646161"><?php echo $rtl["type_name"].",&nbsp;";?>
			</a>
        </span>
						<?php }?>
					</p><br /><br />
					<p><span style="color:#69F">Năm: </span> :
						<?php echo $r['year'];?>
					</p><br />
						<p><span style="color:#69F">Quốc Gia</span> : 
							<?php echo $r['country'];?>
						</p><br />

						<p><span style="color:#69F">Thời Lượng</span> : 
    					<?php echo $r['length_movies'];?> Phút<?php if($r['total_episode']>1) echo '/tập.'; else echo'.'; ?>
						</p><br />
					<?php
					if($r['total_episode']>1){
						echo'<p><span style="color:#69F">Số Tâp: </span>'.$r['total_episode'];
						echo'</p><br />';
					}
					?>

							<p><span style="color:#69F"> Lượt Xem</span> :
						<?php echo $r['total_viewer'];?>
						</p><br />        
                           </div>
            </div>
         </div>
         <div class="assess">
			 <script language="javascript">
				 function a(p)
				 {
					 $.ajax({
						 url : "a1.php", // g?i ajax ??n file result.php
						 type : "get", // ch?n ph??ng th?c g?i là get
						 dateType:"text", // d? li?u tr? v? d?ng text
						 data : {
							 p: p,
							 id: $('#id_f').val()
						 },
						 success : function (result){
							 $('#result').html(result);
						 }
					 });
				 }
			 </script>
			 <?php
			 $sql = "select point from assess where id_film='" .$r['id_film']."' ";
			 $temp = $pdo->query($sql);
			 $data = $temp->fetchAll(PDO::FETCH_ASSOC);
			 $a = [];
			 $t_p=0;
			 $d = 0;
			 foreach ($data as $p) {
				 $a[] = $p['point'];
			 }
			 if(!empty($a)) {
				 $sql_a = "select count(id_film) from assess where id_film='" .$r['id_film']."' ";
				 $temp = $pdo->query($sql_a);
				 $row = $temp->fetchAll(PDO::FETCH_COLUMN);
				 if($row[0]!=null)
					 $d = $row[0];
				 $t_p =array_sum($a) /$row[0];
				 $t_p = substr($t_p,0,1);
				 $t_p = (int)$t_p;
			 }
			 ?>
         <p class="movie-detail-h2" style="margin-left: 10px; margin-bottom: 10px; font-size:20px " >Đánh Giá Phim <span style="font-size: 12px;
    color: #ddd;">(<?php echo $d ?>)</span></p>
			 <div class="star-icon-a">
			
				 <input type="text" hidden id="id_f" value="<?php echo $r['id_film'] ?>">
				 <div id="result" style="color: red; width: 200px; height: 10px;"></div>
				 <div style="float: left; width: 270px;" id="a">
					 <?php
					 for($i =0;$i < $t_p; $i++) {
						 ?>
						 <!---->
						 <img src="image/1.png" id="p" style="width: 20px; height: 20px; float: left" onmouseover="this.src='image/0.png'" onmouseout="this.src='image/1.png'" onclick="a(<?php echo $i+1?>)" alt="<?php echo $i+1?>"">
						 &nbsp;
						 <?php
					 }
						 for ($i = 0; $i <(5 - $t_p); $i++) {
							 ?>
							 <!-- onmouseover="this.src='image/0.png'" onmouseout="this.src='image/star1.png'" -->
							 <img src="image/star1.png" onmouseover="this.src='image/0.png'"
								  onmouseout="this.src='image/star1.png'" style="width: 20px; height: 20px; float: left"
								  onclick="a(<?php echo $t_p + $i + 1 ?>)" alt="<?php echo $t_p + $i + 1; ?>"">

							 &nbsp;
							 <?php
						 }
					 ?>
				 </div>
			 </div>
         </div>
		<div class="xoa"></div>
		<div class="banner-actor">
			<p class="movie-detail-h2" style="margin-left: 10px; margin-bottom: 10px;font-size:20px">Diễn Viên</p>
			<?php
			include"actor_silde.php";
			?>
		</div>
         <div class="tt-film">
			 <p class="movie-detail-h2" style="font-size:20px">Nội Dung Phim</p>
             <p style="margin-left: 10px; margin-bottom: 10px; margin-top:0px; color:#bbb">
         	<?php echo $r['description']; ?></p>
         </div>
    </div>
    <div class="detail-r" style="width:321px">
    	<div class="body-r-t" style="padding-top:0px">
        	<?php 
				include"rank.php";
			?>
        </div>   
	
    </div>
    <div class="xoa"></div>
</div>
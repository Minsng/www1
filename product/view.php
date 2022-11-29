<? 
	session_start(); 
    @extract($_POST);
    @extract($_GET);
    @extract($_SESSION);
	// $table, $num, $page, 세션변수

	include "../lib/dbconn.php";

	$sql = "select * from $table where num=$num";
	$result = mysql_query($sql, $connect);

    $row = mysql_fetch_array($result);       
      // 하나의 레코드 가져오기
	
	$item_num     = $row[num];
	$item_id      = $row[id];
	$item_name    = $row[name];
  	$item_nick    = $row[nick];
	$item_hit     = $row[hit];

	$image_name[0]   = $row[file_name_0];
	$image_name[1]   = $row[file_name_1];
	$image_name[2]   = $row[file_name_2];


	$image_copied[0] = $row[file_copied_0];	// 2022_02_22_10_43_01_0.jpg
	$image_copied[1] = $row[file_copied_1];
	$image_copied[2] = $row[file_copied_2];

    $item_date    = $row[regist_day];
	$item_category_1 = str_replace(" ", "&nbsp;", $row[category_1]);
	$item_category_2 = str_replace(" ", "&nbsp;", $row[category_2]);
	$item_subject = str_replace(" ", "&nbsp;", $row[subject]);

	$item_content = $row[content];
	$is_html      = $row[is_html];

	if ($is_html!="y")
	{
		$item_content = str_replace(" ", "&nbsp;", $item_content);
		$item_content = str_replace("\n", "<br>", $item_content);
	}
	
	// for ($i=0; $i<3; $i++)
	// {
	// 	if ($image_copied[$i]) //업로드한 파일이 존재하면 0 1 2
	// 	{
	// 		$imageinfo = GetImageSize("./data/".$image_copied[$i]);
    //         //GetImageSize(서버에 업로드된 파일 경로 파일명)
    //           // => 파일의 너비와 높이값, 종류
	// 		$image_width[$i] = $imageinfo[0];  //파일너비
	// 		$image_height[$i] = $imageinfo[1]; //파일높이
	// 		$image_type[$i]  = $imageinfo[2];  //파일종류

    //     if ($image_width[$i] > 1200)
	// 			$image_width[$i] = 1200;
	// 	}
	// 	else
	// 	{
	// 		$image_width[$i] = "";
	// 		$image_height[$i] = "";
	// 		$image_type[$i]  = "";
	// 	}
	// }

	$new_hit = $item_hit + 1;

	$sql = "update $table set hit=$new_hit where num=$num";   // 글 조회수 증가시킴
	mysql_query($sql, $connect);

	$ripple = "concert_ripple";
?>
<!DOCTYPE html>
<html lang="ko">
<head> 
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>고객지원 - 제품검색</title>
	<link rel="stylesheet" href="../common/css/common.css">
	<link rel="stylesheet" href="../sub6/common/css/sub6common.css">
	<link rel="stylesheet" href="./css/product.css">
	<script src="https://kit.fontawesome.com/d488d1cfdc.js" crossorigin="anonymous"></script>
	<script src="../common/js/prefixfree.min.js"></script>
	<script>
		function del(href) 
		{
			if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
					document.location.href = href;
			}
		}
	</script>
</head>

<body>
	<? include "../common/sub_header.html" ?>
		<div class="main">
            <img src="../sub6/images/sub6visual.png" alt="서브6비주얼이미지">
            <h3>고객지원</h3>
        </div>
		<div class="subNav">
            <ul>
                <li><a href="../greet/list.php">공지사항</a></li>
                <li><a href="../concert/list.php">뉴스</a></li>
                <li><a href="../product/list.php" class="current">제품검색</a></li>
                <li><a href="../sub6/sub6_4.html">문의하기</a></li>
            </ul>
        </div>
		<article id="content">

            <div class="titleArea">
                <div class="lineMap">
                    <span><i class="fa-solid fa-house"></i></span> &gt; <span>고객지원</span> &gt; <span>제품검색</span>
                </div>
                <h2>제품검색</h2>
            </div>
			<div class="contentArea">
                <!-- 본문 콘텐츠 영역 -->
				<div class="photo_bbs_wrap">

					<ul class="bbs_view_ttl">
						<li>
							<i><?=$item_category_1?></i>
							<i><?=$item_category_2?></i>
							<strong><?= $item_subject ?></strong>
						</li>
						<li>
							<span><?= $item_nick ?></span>
							<span><?= $item_date ?></span>
							<span><i class="fa-regular fa-eye"><i>조회수</i></i> <?= $item_hit ?></span>
						</li>
					</ul>

					
					<div class="bbs_view_cont">
						<?
							for ($i=0; $i<3; $i++)  //업로드된 이미지를 출력한다
							{
								if ($image_copied[$i])	// 첨부된 파일이 있으면
								{
									$img_name = $image_copied[$i];
									$img_name = "./data/".$img_name; 
									// ./data/2019_03_22_10_07_15_0.jpg
									// $img_width = $image_width[$i];
									
									// echo "<div class='img'><img src='$img_name' width='$img_width'></div>";
                                    echo "<div class='img'><img src='$img_name' alt='첨부이미지'></div>";
								}
							}
						?>
						<?= $item_content ?>
					</div>

					<div class="btn_wrap">
						<a href="list.php?table=<?=$table?>&page=<?=$page?>&liststyle=<?=$liststyle?>">목록</a>
						<? 
							if($userid==$item_id || $userlevel==1 || $userid=="admin")
							// 로그인된 아이디 == 글쓴이 이거나 최고 관리자면 참
							{
						?>
						<a href="write_form.php?table=<?=$table?>&mode=modify&num=<?=$num?>&page=<?=$page?>&liststyle=<?=$liststyle?>">수정</a>
						<a href="javascript:del('delete.php?table=<?=$table?>&num=<?=$num?>&liststyle=<?=$liststyle?>')">삭제</a>
						<?
							}
						?>
						<? 
							if($userid)  //로그인이 되면 글쓰기
							{
						?>
						<a href="write_form.php?table=<?=$table?>&liststyle=<?=$liststyle?>" class='active'>글쓰기</a>
						<?
							}
						?>
					</div>
				</div>


			</div>
		</article>
	<? include '../common/sub_footer.html' ?>
</body>
</html>

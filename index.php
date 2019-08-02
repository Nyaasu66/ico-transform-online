<?php
include("./favicon.php");
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="./css/favicon.css" type="text/css" />
	<title>ICO图标在线转换工具</title>
	<script type="text/javascript">
		function imgPreview(fileDom) {
			//判断是否支持FileReader
			if (window.FileReader) {
				var reader = new FileReader();
			} else {
				alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
			}
			//获取文件
			var file = fileDom.files[0];
			var imageType = /^image\//;
			//是否是图片
			if (!imageType.test(file.type)) {
				alert("请选择图片！");
				return;
			}
			//读取完成
			reader.onload = function(e) {
				//获取图片dom
				var img = document.getElementById("preview");
				//图片路径设置为读取的图片
				img.src = e.target.result;
			};
			reader.readAsDataURL(file);
		}
	</script>
	<style>
		img:after {
		content: ' ';
    display: block;
    position: absolute;
    top: 106px;
    width: 20px;
    height: 20px;
    background: #fff;
		}
	</style>
</head>

<body>
	<div id="favicon-area">
		<a style="display: block; text-indent: 0em;" href="http://blog.quietguoguo.com/">
		</a>
		<h1>ICO图标在线转换</h1>
		<form method="post" enctype='multipart/form-data'>
			<div id="favicon-source">
				<img id="preview" height="240px" width="240px" alt="上传源图片">
				</br></br>
				<input type="file" name="upimage" onchange="imgPreview(this)" style="margin:0 28%;">
			</div>

			<div id="favicon-output">
				<div id="favicon-notice">
					<h2>选择尺寸：</h2>
					<div style="margin:3px 1px;">
						<input type="radio" name="size" value="1" id="s1"><label for="s1">16*16</label>
						<input type="radio" name="size" value="4" id="s4" checked><label for="s4">64*64</label>
						<input type="radio" name="size" value="5" id="s5"><label for="s5">128*128</label>
						</br></br>
						<input type="radio" name="size" value="2" id="s2"><label for="s2">32*32</label>
						<input type="radio" name="size" value="3" id="s3"><label for="s3">48*48</label>
						<input type="radio" name="size" value="6" id="s6"><label for="s6">256*256</label>
					</div>
					<h2>需要注意</h2>
					<p>图片应小于512KB且为JPG、GIF或PNG格式，建议长宽比1:1。</p>
					<p style="color:red;"><?php if ($typeinfo != "") {
																	echo $typeinfo;
																} ?></p>
				</div>
				</br>
				<input type="submit" value="生成ICO图标" style="margin:0 36%; float: right;">
			</div>
		</form>
		<div id="favicon-text">
			<h1>Ico？Favicon？</h1>
			<div style="margin:0px 12px;">
				<p>ico是Iconfile的缩写，是Windows计算机中的一种图标文件格式，一般我们电脑桌面上显示的快捷方式就是这种格式的图标。一个ico文件实际上是多张不同尺寸图片的集合体，根据应用场景的不同自动选择合适的图片格式。</p>
				<p>favicon是Favorites Icon的缩写，主要显示在浏览器地址栏左侧以提升网站品牌度。对于大多数主流浏览器，favicon不仅在地址栏中显示，还会同时出现在收藏夹中，用户可以通过拖曳favicon到桌面以建立网站的快捷方式。除此之外，标签式浏览器甚至还有不少扩展的功能，如FireFox甚至支持动画格式的favicon。</p>
			</div>
		</div>
	</div>
	<div id="favicon-area-copyright">
		&copy;2019 | <a href="https://blog.nyaasu.top">Nyaasu</a>
	</div>

</body>

</html>
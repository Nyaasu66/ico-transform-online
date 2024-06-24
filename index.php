<?php
include("./favicon.php");
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta charset="utf-8">
	<meta name="description" content="在线将jpg,png,gif格式图片转换为ico格式图标的开源工具，适合网页favicon，支持透明背景，自带圆角剪裁功能" />
	<meta name="keywords" content="ico 转换 favicon 开源 免费 png jpg 工具 在线" />
	<link rel="stylesheet" href="./index.css" type="text/css" />
	<link rel="icon" type="image/ico" href="https://blog.nyaasu.top/usr/imgs/favicon.ico" />
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
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-MQ5XKRJNTF"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-MQ5XKRJNTF');
	</script>
</head>

<body>
	<div id="favicon-area">
		<h1>ICO 图标在线转换</h1>
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
					<p>图片应小于 1 MB 且为 JPG, GIF, PNG 格式，建议长宽比1:1</p>
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
				<p>ico 是 Iconfile 的缩写，是 Windows 计算机中的一种图标文件格式，一般我们电脑桌面上显示的快捷方式就是这种格式的图标。一个 ico 文件实际上是多张不同尺寸图片的集合体，根据应用场景的不同自动选择合适的图片格式。</p>
				<p>favicon 是 Favorites Icon 的缩写，主要显示在浏览器地址栏左侧以提升网站品牌度。对于大多数主流浏览器，favicon 不仅在地址栏中显示，还会同时出现在收藏夹中，用户可以通过拖曳 favicon 到桌面以建立网站的快捷方式。除此之外，标签式浏览器甚至还有不少扩展的功能，如 FireFox 甚至支持动画格式的 favicon。</p>
			</div>
			<div>
				<p>提示：操作之前，您可先使用 <a href="./transp/index.html">在线透明圆角工具</a>，生成透明圆角图片，然后再进行操作！</p>
			</div>
			<div>
				<p>24.06.12 更新：目前已支持透明背景，源码在底部链接，有条件的可以按照说明自行部署。</p>
			</div>
		</div>
	</div>
	<div id="favicon-area-copyright">
		2019-2024 | <a href="https://blog.nyaasu.top">Nyaasu</a> | <a href="https://github.com/Nyaasu66/ico-transform-online">Github</a>
	</div>

</body>

</html>
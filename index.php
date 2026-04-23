<?php
include("./favicon.php");
?>
<!DOCTYPE html>
<html lang="zh-CN" id="html-root">

<head>
	<meta charset="utf-8">
	<meta name="description" id="meta-desc" content="在线将jpg,png图片转为ico图标，免费开源，适合网站favicon，支持透明背景，自带圆角剪裁功能" />
	<meta name="keywords" content="ico 格式转换 favicon 在线工具 免费开源 png jpg png转ico jpg转ico 透明 github" />
	<!-- Open Graph -->
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://ico.nyaasu.top" />
	<meta property="og:title" content="ICO图标在线转换工具" />
	<meta property="og:description" content="在线将 JPG、PNG 图片转为 ICO 图标，免费开源，支持透明背景与圆角裁剪，适合网站 favicon 制作。" />
	<meta property="og:image" content="https://blog.nyaasu.top/usr/imgs/favicon.ico" />
	<meta property="og:locale" content="zh_CN" />
	<meta property="og:locale:alternate" content="en_US" />
	<!-- Twitter Card (Telegram fallback) -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="ICO图标在线转换工具" />
	<meta name="twitter:description" content="在线将 JPG、PNG 图片转为 ICO 图标，免费开源，支持透明背景与圆角裁剪，适合网站 favicon 制作。" />
	<meta name="twitter:image" content="https://blog.nyaasu.top/usr/imgs/favicon.ico" />
	<link rel="stylesheet" href="./index.css?v=<?= filemtime(__DIR__ . '/index.css') ?>" type="text/css" />
	<link rel="icon" type="image/ico" href="https://blog.nyaasu.top/usr/imgs/favicon.ico" />
	<title id="page-title">ICO图标在线转换工具</title>
	<script type="text/javascript">
		var I18N = {
			zh: {
				htmlLang: 'zh-CN',
				pageTitle: 'ICO图标在线转换工具',
				metaDesc: '在线将jpg,png图片转为ico图标，免费开源，适合网站favicon，支持透明背景，自带圆角剪裁功能',
				mainTitle: 'ICO 图标在线转换',
				uploadAlt: '上传源图片',
				sizeLabel: '选择尺寸：',
				noticeTitle: '需要注意',
				noticeP1: '图片应小于 1 MB 且为 JPG, GIF, PNG 格式，建议长宽比1:1',
				submitBtn: '生成ICO图标',
				introTitle: 'ico、favicon 是什么？',
				introP1: 'ico 是 icon file 的缩写，是 Windows 计算机中的一种图标文件格式，一般我们电脑桌面上显示的快捷方式就是这种格式的图标。一个 ico 文件实际上是多张不同尺寸图片的集合体，根据应用场景的不同自动选择合适的图片格式。',
				introP2: 'favicon 是 Favorites Icon 的缩写，主要显示在浏览器地址栏左侧以提升网站品牌度。对于大多数主流浏览器，favicon 不仅在地址栏中显示，还会同时出现在收藏夹中，用户可以通过拖曳 favicon 到桌面以建立网站的快捷方式。除此之外，标签式浏览器甚至还有不少扩展的功能，如 FireFox 甚至支持动画格式的 favicon。',
				tipHtml: '提示：操作之前，您可先使用 <a href="./transp/index.html">在线透明圆角工具</a>，生成透明圆角图片，然后再进行操作！',
				updateText: '24.06.12 更新：目前已支持透明背景，源码在底部链接，有条件的可以按照说明自行部署。',
				alertNoFileReader: '您的设备不支持图片预览功能，如需该功能请升级您的设备！',
				alertNotImage: '请选择图片！'
			},
			en: {
				htmlLang: 'en',
				pageTitle: 'ICO Icon Online Converter',
				metaDesc: 'Convert jpg, png images to ico icons online. Free and open source, supports transparent background and rounded corners.',
				mainTitle: 'ICO Icon Online Converter',
				uploadAlt: 'Upload source image',
				sizeLabel: 'Select size:',
				noticeTitle: 'Notice',
				noticeP1: 'Image must be under 1 MB in JPG, GIF, or PNG format. Recommended aspect ratio is 1:1.',
				submitBtn: 'Generate ICO',
				introTitle: 'What is ICO / Favicon?',
				introP1: 'ICO is short for Iconfile, a common icon file format on Windows. Desktop shortcuts typically use this format. An ICO file is a collection of images in multiple sizes, automatically selecting the appropriate size based on context.',
				introP2: 'Favicon stands for Favorites Icon, displayed in the browser address bar to enhance brand recognition. For most browsers, the favicon appears in the address bar and bookmarks alike. Users can drag the favicon to the desktop to create a website shortcut. Some browsers like Firefox even support animated favicons.',
				tipHtml: 'Tip: Before converting, you can use the <a href="./transp/index.html">online transparent rounded corner tool</a> to generate a rounded transparent image first!',
				updateText: 'Update 2024.06.12: Transparent background is now supported. Source code link is at the bottom. Feel free to self-host following the instructions.',
				alertNoFileReader: 'Your device does not support image preview. Please upgrade your browser.',
				alertNotImage: 'Please select an image file!'
			}
		};

		var currentLang = 'zh';

		function detectLang() {
			var saved = localStorage.getItem('ico_lang');
			if (saved === 'zh' || saved === 'en') return saved;
			var nav = (navigator.language || navigator.userLanguage || 'zh').toLowerCase();
			return nav.startsWith('zh') ? 'zh' : 'en';
		}

		function applyLang(lang) {
			currentLang = lang;
			var t = I18N[lang];
			document.getElementById('html-root').setAttribute('lang', t.htmlLang);
			document.title = t.pageTitle;
			document.getElementById('meta-desc').setAttribute('content', t.metaDesc);
			document.getElementById('main-title').textContent = t.mainTitle;
			document.getElementById('preview').setAttribute('alt', t.uploadAlt);
			document.getElementById('preview-placeholder').textContent = t.uploadAlt;
			document.getElementById('size-label').textContent = t.sizeLabel;
			document.getElementById('notice-title').textContent = t.noticeTitle;
			document.getElementById('notice-p1').textContent = t.noticeP1;
			document.getElementById('submit-btn').value = t.submitBtn;
			document.getElementById('intro-title').textContent = t.introTitle;
			document.getElementById('intro-p1').textContent = t.introP1;
			document.getElementById('intro-p2').textContent = t.introP2;
			document.getElementById('tip-p').innerHTML = t.tipHtml;
			document.getElementById('update-p').textContent = t.updateText;
			document.getElementById('lang-input').value = lang;

			var btnZh = document.getElementById('btn-zh');
			var btnEn = document.getElementById('btn-en');
			if (lang === 'zh') {
				btnZh.classList.add('active');
				btnEn.classList.remove('active');
			} else {
				btnEn.classList.add('active');
				btnZh.classList.remove('active');
			}
		}

		function setLang(lang) {
			localStorage.setItem('ico_lang', lang);
			applyLang(lang);
		}

		function imgPreview(fileDom) {
			if (window.FileReader) {
				var reader = new FileReader();
			} else {
				alert(I18N[currentLang].alertNoFileReader);
				return;
			}
			var file = fileDom.files[0];
			var imageType = /^image\//;
			if (!imageType.test(file.type)) {
				alert(I18N[currentLang].alertNotImage);
				return;
			}
		reader.onload = function(e) {
			var img = document.getElementById("preview");
			img.src = e.target.result;
			img.style.display = '';
			document.getElementById("preview-placeholder").style.display = 'none';
		};
			reader.readAsDataURL(file);
		}

		window.addEventListener('DOMContentLoaded', function() {
			applyLang(detectLang());
		});
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
	<div id="lang-switcher">
		<button id="btn-zh" onclick="setLang('zh')">中</button>
		<button id="btn-en" onclick="setLang('en')">EN</button>
	</div>
	<div id="favicon-area">
		<h1 id="main-title">ICO 图标在线转换</h1>
		<form method="post" enctype='multipart/form-data'>
			<input type="hidden" name="lang" id="lang-input" value="zh">
			<div id="favicon-source">
			<div id="preview-placeholder"></div>
			<img id="preview" height="240px" width="240px" style="display:none;">
			</br></br>
			<input type="file" name="upimage" onchange="imgPreview(this)" style="margin:0 28%;">
			</div>

			<div id="favicon-output">
				<div id="favicon-notice">
					<h2 id="size-label">选择尺寸：</h2>
					<div style="margin:3px 1px;">
						<input type="radio" name="size" value="1" id="s1"><label for="s1">16*16</label>
						<input type="radio" name="size" value="4" id="s4" checked><label for="s4">64*64</label>
						<input type="radio" name="size" value="5" id="s5"><label for="s5">128*128</label>
						</br></br>
						<input type="radio" name="size" value="2" id="s2"><label for="s2">32*32</label>
						<input type="radio" name="size" value="3" id="s3"><label for="s3">48*48</label>
						<input type="radio" name="size" value="6" id="s6"><label for="s6">256*256</label>
					</div>
					<h2 id="notice-title">需要注意</h2>
					<p id="notice-p1">图片应小于 1 MB 且为 JPG, GIF, PNG 格式，建议长宽比1:1</p>
					<p style="color:red;"><?php if ($typeinfo != "") {
																echo $typeinfo;
															} ?></p>
				</div>
				</br>
				<input type="submit" id="submit-btn" value="生成ICO图标" style="margin:0 36%; float: right;">
			</div>
		</form>
		<div id="favicon-text">
			<h1 id="intro-title">ico、favicon 是什么？</h1>
			<div style="margin:0px 12px;">
				<p id="intro-p1">ico 是 icon file 的缩写，是 Windows 计算机中的一种图标文件格式，一般我们电脑桌面上显示的快捷方式就是这种格式的图标。一个 ico 文件实际上是多张不同尺寸图片的集合体，根据应用场景的不同自动选择合适的图片格式。</p>
				<p id="intro-p2">favicon 是 Favorites Icon 的缩写，主要显示在浏览器地址栏左侧以提升网站品牌度。对于大多数主流浏览器，favicon 不仅在地址栏中显示，还会同时出现在收藏夹中，用户可以通过拖曳 favicon 到桌面以建立网站的快捷方式。除此之外，标签式浏览器甚至还有不少扩展的功能，如 FireFox 甚至支持动画格式的 favicon。</p>
			</div>
			<div>
				<p id="tip-p">提示：操作之前，您可先使用 <a href="./transp/index.html">在线透明圆角工具</a>，生成透明圆角图片，然后再进行操作！</p>
			</div>
			<div>
				<p id="update-p">24.06.12 更新：目前已支持透明背景，源码在底部链接，有条件的可以按照说明自行部署。</p>
			</div>
		</div>
	</div>
	<div id="favicon-area-copyright">
		2019-2026 | <a href="https://blog.nyaasu.top">Nyaasu</a> | <a href="https://github.com/Nyaasu66/ico-transform-online">Github</a>
	</div>

</body>

</html>

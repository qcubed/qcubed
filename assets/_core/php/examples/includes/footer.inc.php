<?php	if(!isset($mainPage)) { ?>
			<button id="viewSource" onclick="javascript:ViewSource(<?php _p(Examples::GetCategoryId() . ',' . Examples::GetExampleId()); ?>);">View Source</button>
<?php	} ?>
		</section>
		<footer>
			<div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="<?php _p(__VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/qcubed_logo_footer.png'); ?>" alt="QCubed Framework" /> <span class="version"><?php _p(QCUBED_VERSION); ?></span></a></div>
		</footer>
		
		<script type="text/javascript">
			function ViewSource(intCategoryId, intExampleId, strFilename) {
				var fileNameSection = "";
				if (arguments.length == 3) {
					fileNameSection = "/" + strFilename;
				}
				var objWindow = window.open("<?php echo __VIRTUAL_DIRECTORY__ . __EXAMPLES__ ?>/view_source.php/" + intCategoryId + "/" + intExampleId + fileNameSection, "ViewSource", "menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=750,left=50,top=50");
				objWindow.focus();
			}
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));

			try {
				var pageTracker = _gat._getTracker("UA-7231795-1");
				pageTracker._trackPageview();
			} catch(err) {}
		</script>
	</body>
</html>
<script async type="application/javascript" src="https://news.google.com/swg/js/v1/swg-basic.js"></script>
<script>
	(self.SWG_BASIC = self.SWG_BASIC || [ ]).push(basicSubscriptions => {
		basicSubscriptions.init(<?php echo json_encode($params); ?>);
	});
</script>

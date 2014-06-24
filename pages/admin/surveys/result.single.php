<div class="wrap">

	<div id="icon-tools" class="icon32"></div>
	<h2>WP Survey And Quiz Tool - Survey Result</h2>
		
	<?php require WPSQT_DIR.'pages/admin/misc/navbar.php'; ?>
		
	<?php if (!empty($result['person'])) { ?>
		<h3>Quiz Details</h3>
		<div class="person">
		
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<?php foreach($result['person'] as $fieldName => $fieldValue){
					if(!strpos($fieldValue, "@")) { ?>
						<tr>
							<th scope="row"><?php echo esc_html(strip_tags(wp_kses_stripslashes($fieldName))); ?></th>
							<td><?php echo esc_html(strip_tags(wp_kses_stripslashes($fieldValue))); ?></td>
						</tr>
					<?php }
				} ?>
			</table>
		</div>
	<?php } ?>
	
	<?php 
	$answerNames = ['Yes', 'No', 'Not sure'];
	$answerColours = ['#43a117', '#c91616', '#e19d17'];
	foreach ( $result['sections'] as $section ){ ?>
		<h3><?php echo $section['name']; ?></h3>
		<table id="survey-answers">
			<tr>
				<th>Question</th>
				<th>Judgement</th>
				<th>Comment</th>
			</tr>
		<?php
			if (!isset($section['questions'])){
				continue;
			}
			foreach ($section['questions'] as $questionKey => $questionArray) {
			
				$questionId = $questionArray['id'];
				?>
				<tr>	
				<td><?php print stripslashes($questionArray['name']); ?></td>
				<?php 
				$givenAnswerName = '';
				$givenAnswerColour = '';
				if(isset($section['answers'][$questionId]['given'])) {
					$givenAnswer = $section['answers'][$questionId]['given'][0];
					$givenAnswerName = $answerNames[$givenAnswer];
					$givenAnswerColour = $answerColours[$givenAnswer];
				} ?>
				
				<td width=80 style="background:<?php echo $givenAnswerColour ?>"><div class="table-answer"><?php echo $givenAnswerName ?></div></td>
				<td><?php echo $section['comment'][$questionKey][0] ?></td>
 				
			</tr>
		<?php } ?>
		</table>
	<?php } ?>

</div>
<?php require_once WPSQT_DIR.'/pages/admin/shared/image.php'; ?>
<?php // New results page added by wb

	$answerNames = ['Yes', 'Unclear', 'No'];
	$answerColours = ['#43a117', '#e19d17', '#c91616'];

	foreach ( $sections as $sectionKey => $section ) { ?>
		<h3><?php // echo $section['name']; ?></h3>
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
				//var_dump($questionArray['answers']);
				$givenAnswerName = '';
				$givenAnswerColour = '';
				if(isset($section['answers'][$questionId]['given'])) {
					$givenAnswer = $section['answers'][$questionId]['given'][0];
					//echo 'givenanswer: ', $givenAnswer;
					$givenAnswerName = $answerNames[$givenAnswer];
					$givenAnswerColour = $answerColours[$givenAnswer];
				} ?>
				
				<td width=80 style="background:<?php echo $givenAnswerColour ?>"><div class="table-answer"><?php echo $givenAnswerName ?></div></td>
				<td><?php echo $section['comment'][$questionKey][0] ?></td>
 				
			</tr>
		<?php } ?>
		</table>
	<?php } ?>
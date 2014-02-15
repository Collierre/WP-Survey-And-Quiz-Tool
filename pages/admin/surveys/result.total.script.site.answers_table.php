<?php // New results page added by wb

	foreach ( $sections as $sectionKey => $section ) { ?>
		<h3><?php // echo $section['name']; ?></h3>
		<table id="survey-answers">
			<tr>
				<th>Question</th>
				<th>How satisfied are you? Why?</th>
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
			<?php if ( ucfirst($questionArray['type']) == 'Multiple' 
					|| ucfirst($questionArray['type']) == 'Single' 
					|| ucfirst($questionArray['type']) == "Multiple Choice" 
					|| ucfirst($questionArray['type']) == "Dropdown" ){
				?>				
				<td>
						<?php foreach ($questionArray['answers'] as $answerKey => $answer){
						    if($answerKey == 0) $givenAnswerColour = "#43a117";
						    elseif($answerKey == 1) $givenAnswerColour = "#c91616";
						    elseif($answerKey == 2) $givenAnswerColour = "#e19d17";
						    else $givenAnswerColour = "#000"; ?>
							  <?php if (isset($section['answers'][$questionId]['given']) && in_array($answerKey, $section['answers'][$questionId]['given']) ) { 
							  echo "<span style='color: " . $givenAnswerColour . ";'>" . stripslashes($answer['text']) . "</span>";
							   }?>
						<?php } ?>
				</td>
			<?php } else if (ucfirst($questionArray['type']) == 'Likert') {
					?><p></p><b><u>Answer Given</u>:&nbsp;</b><?php if(isset($section['answers'][$questionId]['given'])) { echo $section['answers'][$questionId]['given']; } else { echo 'None'; } ?> </p> <?php
				} else if (ucfirst($questionArray['type']) == 'Likert Matrix') {
					echo '<ul>';
					foreach ($section['answers'][$questionId]['given'] as $givenAnswer) {
						if (is_array($givenAnswer)) {
							$otherText = $givenAnswer['text'];
							$givenAnswer = explode("_", $givenAnswer[0]);
							echo '<li><strong>'.$otherText.'</strong> - '.$givenAnswer[1].'</li>';
						} else {
							$givenAnswer = explode("_", $givenAnswer);
							echo '<li><strong>'.$givenAnswer[0].'</strong> - '.$givenAnswer[1].'</li>';
						}
					}
					echo '</ul>';
				} else {
				?>				
				<b><u>Answer Given</u></b>
				<p class="answer_given" style="background-color : #c0c0c0; border : 1px dashed black; padding : 5px;overflow:auto;height : 200px;"><?php if ( isset($section['answers'][$questionId]['given']) && is_array($section['answers'][$questionId]['given']) ){ echo nl2br(esc_html(stripslashes(current($section['answers'][$questionId]['given'])))); } ?></p>
			<?php } ?>
            </tr>
		<?php } ?>
		</table>
	<?php } ?>
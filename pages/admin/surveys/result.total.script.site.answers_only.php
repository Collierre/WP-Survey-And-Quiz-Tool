<?php // New results page added by wb

if ( $sections == false ) { ?>

	<p>There are no results for this survey yet.</p>

<?php } else { ?>

	<?php foreach ( $sections as $sectionKey => $section ) { ?>
		<h3><?php // echo $section['name']; ?></h3>
		
		<?php
			if (!isset($section['questions'])){
				continue;
			}
			foreach ($section['questions'] as $questionKey => $questionArray) { 
			
				$questionId = $questionArray['id'];
				?>
				
			<h4><?php print stripslashes($questionArray['name']); ?></h4>
			<?php if ( ucfirst($questionArray['type']) == 'Multiple' 
					|| ucfirst($questionArray['type']) == 'Single' 
					|| ucfirst($questionArray['type']) == "Multiple Choice" 
					|| ucfirst($questionArray['type']) == "Dropdown" ){
				?>				
				<b><u>Answers</u></b>
				<p class="answer_given">
					<ol>
						<?php foreach ($questionArray['answers'] as $answerKey => $answer){
						    if($answerKey == 0) $givenAnswerColour = "#43a117";
						    elseif($answerKey == 1) $givenAnswerColour = "#c91616";
						    elseif($answerKey == 2) $givenAnswerColour = "#e19d17";
						    else $givenAnswerColour = "#000"; ?>
							  <li><font color="<?php echo ( !isset($answer['correct']) || $answer['correct'] != 'yes' ) ?  (isset($section['answers'][$questionId]['given']) &&  in_array($answerKey, $section['answers'][$questionId]['given']) ) ? $givenAnswerColour :  '#000000' : '#00FF00' ; ?>"><?php echo stripslashes($answer['text']) ?></font><?php if (isset($section['answers'][$questionId]['given']) && in_array($answerKey, $section['answers'][$questionId]['given']) ){ ?> - Given<?php }?></li>
						<?php } ?>
					</ol>
				</p>
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
			<?php }
			if($section['comment']) { ?>
                <ul style="margin-left:0.9em;">
                    <li style="list-style-type:none"><span style="font-style:italic;">Comment:</span> <?php echo $section['comment'][$questionKey][0]; ?></li>
                </ul>
            <?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>
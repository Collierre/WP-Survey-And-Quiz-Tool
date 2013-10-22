			<ul class="wpsqt_multiple_question">
			<?php
				if (isset($question['randomize_answers']) && $question['randomize_answers'] == 'yes') {
					$answers = array();
					while (count($question['answers']) > 0) {
						$key = array_rand($question['answers']);
						$answers[$key] = $question['answers'][$key];
						unset($question['answers'][$key]);
					}
					$question['answers'] = $answers;

					// Store the order of the answers for review page
					$_SESSION['wpsqt'][$quizName]['sections'][$sectionKey]['questions'][$questionKey]['answers'] = $answers;
				}
			?>
			<?php foreach ( $question['answers'] as $answerKey => $answer ) { ?>
				<li>
				<!-- wb changed checkbox to radio for multiple choice questions -->
					<input type="<?php echo ($question['type'] == 'Single' ) ? 'radio' : 'radio'; ?>" name="answers[<?php echo $questionKey; ?>][]" value="<?php echo $answerKey; ?>" id="answer_<?php echo $question['id']; ?>_<?php echo $answerKey;?>" <?php if ( (isset($answer['default']) && $answer['default'] == 'yes') || in_array($answerKey, $givenAnswer)) {  ?> checked="checked" <?php } ?> /> <label for="answer_<?php echo $question['id']; ?>_<?php echo $answerKey;?>"><?php echo esc_html( $answer['text'] ); ?></label>
				</li>
			<?php } ?>
			</ul>
			<?php
			// wb modified code below from unused 'other' field to comment field
			if (    $question['type'] == 'Multiple Choice' && array_key_exists('comment_field',$question) && $question['comment_field'] == 'yes' ){ ?>
				<p><textarea rows="6" cols="50" name="comment[<?php echo $questionKey; ?>][]" placeholder="<?php echo $question['comment_label']; ?>" value=""></textarea></p>
				<?php } ?>
			</ul>
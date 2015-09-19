<!-- WordPress styleguide compatible field list. Use with load_view('admin-fields',$fields) -->
<table class="form-table">
	<tbody>
		<? foreach( $fields as $field ) : ?>
			<tr>
				<th scope="row">
					<label for="<?= $field['name'] ?>"><?= $field['title'] ?></label>
				</th>
				<td>
					<? switch($field['type']) : 
						case'select': ?>
							<select id="<?= $field['name'] ?>" name="<?= $field['name'] ?>">
								<? foreach( $field['options'] as $value => $option ) : ?>
									<option value="<?= $value ?>" <?= isset($field['value']) && $field['value'] == $value ? 'selected' : '' ?>><?= $option ?></option>
								<? endforeach ?>
							</select>
						<? break;
						case'text': ?>
							<input type="text" id="<?= $field['name'] ?>" name="<?= $field['name'] ?>" value="<?= $field['value'] ?>" >
						<? break;
						case'checkbox': ?>
							<input type="checkbox" <?= isset($field['value']) && $field['value'] == '1' ? 'checked="checked"' : '' ?> value="1" id="<?= $field['name'] ?>" name="<?= $field['name'] ?>">
						<? break;?>
						
					<? endswitch ?>
					<? if( !empty($field['description']) ) : ?>
						<br />
						<span class="description"><?= $field['description'] ?></span>
					<? endif ?>
				</td>
			</tr>
		<? endforeach ?>
	</tbody>
</table>
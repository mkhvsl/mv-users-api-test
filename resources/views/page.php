<?php get_header(); ?>

<?php if($users) { ?>
<table class="uk-table uk-table-divider" id="mv-users-api-test">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Username</th>
		</tr>
	</thead>
	<?php foreach($users as $user) { ?>
	<tr>
		<td><a href="#" data-id="<?php echo $user->id ?>"><?php echo $user->id ?></a></td>
		<td><a href="#" data-id="<?php echo $user->id ?>"><?php echo $user->name ?></a></td>
		<td><a href="#" data-id="<?php echo $user->id ?>"><?php echo $user->username ?></a></td>
	</tr>
	<?php } ?>
	</table>
<?php } else { ?>
<p>Error, please contact site administrator</p>
<?php } ?>

<div id="lk-modal" uk-modal>
	<div class="uk-modal-dialog">
		<button class="uk-modal-close-default" type="button" uk-close></button>
		<div class="uk-modal-body"></div>
	</div>
</div>

<?php get_footer(); ?>
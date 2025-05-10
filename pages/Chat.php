<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/messages_func.php';
$message_id = validation($_GET['message_id']);
$role = new role;
?>
<main class="content">
    <div class="container p-0">
        <?
            $messages = new Messages;
            $messages->chating();
        ?>
		<div class="card screen-chat" style="">
		<div class="row">
			<div class="col-lg-5 col-xl-12 border-right" id="result-chat"></div>
			
			<div class="ml-10 p-5 text-left write-logo">
				<img src="./icons/write.gif" width="55">
			</div>
				<?
					$rows_message = $messages->rows_m($message_id);
					if ($role->r('role') == 1) {
						if ($rows_message > 0) {?>
						<div class="flex-grow-0 py-3 px-4 border-top m-2">
							<div class="form-group">
								<textarea class="form-control" rows="5" id="body_chat" placeholder=" أكتب رسالتك ..."></textarea>
								<button type="button" class="btn btn-primary m-3" id="send_chat" data-message_id="<? echo $message_id;?>">ارسال</button>
							</div>
						</div>
					<?}
					}elseif($role->r('role') == 2){?>
						<div class="flex-grow-0 py-3 px-4 border-top m-2">
							<div class="form-group">
								<textarea class="form-control" rows="5" id="body_chat" placeholder=" أكتب رسالتك ..."></textarea>
								<button type="button" class="btn btn-primary m-3" id="send_chat" data-message_id="<? echo $message_id;?>">ارسال</button>
							</div>
						</div>
					<?}
				?>
			</div>
		</div>
	</div>
</main>
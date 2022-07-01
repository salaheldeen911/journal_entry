<?php
require_once 'dbConfig.php';
require_once 'helpers.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="assets/icons/css/materialdesignicons.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

	<title>Journal Entry</title>
</head>

<body>
	<main class="container mb-5 mt-5">

		<div class="row clone mt-4" style="display:none">
			<div class="col-md-6">
				<div class="form-group mb-3">
					<label class="mr-2 mt-2">Account</label>
					<select class="form-control accounts">
						<option value='-1' selected disabled>-- Select an account --</option>
						<?php
						foreach (getAllMasterAccounts() as $account) {
						?>
							<option value="<?php echo $account['Account_ID'] ?>"><?php echo $account['Account_Name']; ?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group mb-3">
					<label class="mr-2 mt-2">Amount</label>
					<input type="text" class="form-control amount">
				</div>
			</div>
			<div class="col-md-1 mt-3">
				<i class="mdi mdi-close-circle text-danger delete"></i>
			</div>
		</div>

		<div class="card">
			<h3 class="card-header">Journal Entry</h3>
			<div id='journalEntry' class="card-body">

				<section id="debits">
					<h5 class="card-title mb-4 d-inline m-0">Debit</h5>
					<i class="mdi mdi-plus-circle text-success plus"></i>

					<div class="row mt-4">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="mr-2 mt-2">Account</label>
								<select class="form-control accounts">
									<option value='-1' required selected disabled>-- Select an account --</option>

									<?php
									foreach (getAllMasterAccounts() as $account) {
									?>
										<option value="<?php echo $account['Account_ID'] ?>"><?php echo $account['Account_Name']; ?></option>
									<?php
									}
									?>

								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group mb-3">
								<label class="mr-2 mt-2">Amount</label>
								<input type="text" class="form-control amount">
							</div>
						</div>
						<div class="col-md-1"></div>
					</div>
				</section>

				<hr>

				<section id="credits">
					<h5 class="card-title mb-4 d-inline m-0">Credit</h5>
					<i class="mdi mdi-plus-circle text-success plus"></i>

					<div class="row mt-4">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="mr-2 mt-2">Account</label>
								<select class="form-control accounts">
									<option value='-1' selected disabled>-- Select an account --</option>

									<?php
									foreach (getAllMasterAccounts() as $account) {
									?>
										<option value="<?php echo $account['Account_ID'] ?>"><?php echo $account['Account_Name']; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group mb-3">
								<label class="mr-2 mt-2">Amount</label>
								<input type="text" class="form-control amount">
							</div>
						</div>
						<div class="col-md-1"></div>
					</div>
				</section>

				<hr>

				<section class="details">
					<h5 class="card-title mb-4" style='display:inline;'>Details</h5>

					<div class="row mt-4">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="mr-2 mt-2">Date</label>
								<input id='entryDate' name="date" type="date" class="form-control">
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group mb-3">
								<label class="mr-2 mt-2">Number</label>
								<input id='entryNumber' name="number" type="text" class="form-control">
							</div>
						</div>

					</div>
				</section>

				<hr>

				<section class="note">
					<h5 class="card-title mb-4" style='display:inline;'>Note</h5>

					<div class="row mt-4">
						<div class="col-md-12">
							<div class="form-group mb-3">
								<textarea id='entryNote' name="note" class="form-control" data-spry="textarea" rows="4"></textarea>
							</div>
						</div>

					</div>
				</section>

				<p id="succeeded" class="text-center text-success none"></p>
				<p id="notValid" class="text-center text-danger none"> All fields are required</p>
				<p id="notBalanced" class="text-center text-danger none"> Check your balance</p>

			</div>
			<button id="submitBtn" class="btn btn-primary mb-4">Submit</button>

		</div>
	</main>

	<script src="assets/js/jquery.3.5.1.min.js"></script>

	<script>
		const toDay = new Date().toISOString().substring(0, 10);
		let data = {};
		let slectedAccounts = ['-1'];
		let previous = "";
		let current = "";
		let that = null;

		$(document).ready(function() {
			$('#entryDate').val(toDay);
		});

		$('.plus').on('click', function() {
			let clone = $('.clone').first().clone();

			if ($(this).parent('#debits').length) {
				$('#debits').append(clone);
			} else if ($(this).parent('#credits').length) {
				$('#credits').append(clone);
			}

			clone.addClass('active');
			refreshAccounts();
		})

		$(document).on('click', '.delete', function() {
			let row = $(this).parents('.row').first();
			let selectedAccountValue = row.find('select').first().children("option:selected").val();

			if (slectedAccounts.includes(selectedAccountValue) && selectedAccountValue !== '-1') {
				var accountIndex = slectedAccounts.indexOf(selectedAccountValue);
				slectedAccounts.splice(accountIndex, 1);
			}

			row.remove();
			refreshAccounts();
		});

		$(document).on('focus', 'select.accounts', function() {
			that = this;
			previous = this.value;
		}).on('change', 'select.accounts', function() {
			current = that.value;

			if (slectedAccounts.includes(previous) && previous !== '-1') {
				var accountIndex = slectedAccounts.indexOf(previous);
				slectedAccounts.splice(accountIndex, 1);
			}
			slectedAccounts.push(current);

			refreshAccounts();

			that.blur();
			previous = "";
			current = "";
			that = null;
		});

		$('#submitBtn').on('click', function() {
			this.disabled = true;
			let _this = this

			if (!isBalanced()) {
				$('#notBalanced').fadeIn(1200);
				$('#notBalanced').fadeOut(1200);

				this.disabled = false;

				return false;
			}

			if (!isValid()) {
				$('#notValid').fadeIn(1200);
				$('#notValid').fadeOut(1200);

				this.disabled = false;

				return false;
			}

			fillUp("debits");
			fillUp("credits");

			data['date'] = $('#journalEntry').find('input[name=date]').val();
			data['number'] = $('#journalEntry').find('input[name=number]').val();
			data['note'] = $('#journalEntry').find('textarea[name=note]').val();

			$.ajax({
				url: "create_journal_entry.php",
				dataType: 'json',
				type: "POST",
				data: data,
				success: function(resultBack) {
					$('#succeeded').text(resultBack.message);
					$('#succeeded').show(1000);

					setTimeout(function() {
						$('#succeeded').hide();
					}, 3000)

					slectedAccounts = ['-1']
					$('.amount').val('');
					$('#entryNote').val('');
					$('#entryNumber').val('');
					$('select').prop('selectedIndex', 0);
					$('#entryDate').val(toDay);
					$('.active').remove();

					refreshAccounts();
				},
				error: function(xhr, ajaxOptions, thrownerror) {
					console.log(thrownerror);
				}
			})
			_this.disabled = false;
		})

		function isValid() {
			let container = $('#journalEntry');
			let inputs = container.find(':input');
			let validated = true;

			for (let input of inputs) {
				$(input).removeClass('inValid');
				$(input).addClass('valid');

				if (input.value == '' || input.value == '-1') {
					$(input).removeClass('valid');
					$(input).addClass('inValid');

					if (validated) {
						validated = false;
					}
				}
			}

			return validated;
		}

		function isBalanced() {
			let totalDebit = 0;
			let totalCredit = 0;

			let debitAmonts = $('#debits').find('input.amount');
			for (let debit of debitAmonts) {
				totalDebit += parseFloat(debit.value);
			}

			let creditAmonts = $('#credits').find('input.amount');
			for (let credit of creditAmonts) {
				totalCredit += parseFloat(credit.value);
			}

			if (totalCredit === totalDebit) {
				data['total_c'] = totalCredit;
				data['total_d'] = totalDebit;
				return true
			}

			$('#journalEntry').find('input.amount').addClass('inValid');
			$('#journalEntry').find('input.amount').removeClass('valid');

			return false;
		}

		function refreshAccounts() {
			let container = $('#journalEntry');
			let options = container.find('option');

			for (let option of options) {
				if (slectedAccounts.includes(option.value) || option.value == "-1") {
					option.disabled = true;
				} else {
					option.disabled = false;
				}
			}
		}

		function fillUp(type) {
			let container = $(`#${type}`);
			let rows = container.find('.row');
			let object = data[type] = {};

			for (let row of rows) {
				let acountName = $(row).find('select').first().find('option:selected').text();
				let acountId = $(row).find('select').first().find('option:selected').val();
				let accountAmount = $(row).find('.amount').first().val();

				object[acountName] = {
					'id': acountId,
					'amount': accountAmount
				}
			}
		}
	</script>
</body>

</html>
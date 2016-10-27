<?php $__env->startSection('registration'); ?>
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col"></div>

    <div class="mdl-cell mdl-cell--8-col">
        <div class="mdl-card mdl-shadow--2dp registration_card">
          <?php echo Form::open(['style'=>'width:100%']); ?>

          <div class="mdl-card__title mdl-card--expand">
            <h4>
              First, Let's Get Your McGill E-mail and Password for Your New Account.
            </h4>
          </div>
          <div class="mdl-card__actions mdl-card--border">
            <ul class="list-style-none">
              <?php foreach($errors->all() as $error): ?>
                  <li class="submit_error"><?php echo e($error); ?></li>
              <?php endforeach; ?>
            </ul>


            <table class="bi_info_table">

              <tr>
                <td>
                  McGill E-Mail
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    <?php echo Form::label('Email', 'E-mail', ['class'=> 'mdl-textfield__label']); ?>

                    <?php echo Form::email('Email', null, ['class'=> 'mdl-textfield__input', 'value'=>old('e-mail')]); ?>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  First Name
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    <?php echo Form::label('First_Name', 'First Name', ['class'=> 'mdl-textfield__label']); ?>

                    <?php echo Form::text('First_Name', null, ['class'=> 'mdl-textfield__input']); ?>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  Last Name
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    <?php echo Form::label('Last_Name', 'Last Name', ['class'=> 'mdl-textfield__label']); ?>

                    <?php echo Form::text('Last_Name', null, ['class'=> 'mdl-textfield__input']); ?>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  Password
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    <?php echo Form::label('Password', 'Password', ['class'=> 'mdl-textfield__label']); ?>

                    <?php echo Form::password('Password', ['class'=> 'mdl-textfield__input']); ?>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  Confirm Password
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    <?php echo Form::label('Confirm_Password', 'Confirm Password', ['class'=> 'mdl-textfield__label']); ?>

                    <?php echo Form::password('Confirm_Password', ['class'=> 'mdl-textfield__input']); ?>

                  </div>
                </td>
              </tr>

              <?php /* <tr>
                <td>
                  Your Current Faculty
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
                  <?php echo Form::select('Faculty', $faculties, null, ['class'=> 'reg_dropdown form-control', 'id'=>'faculty-select']); ?>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  Your Current Major
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
                    <select name="Major" id="major-select" class="reg_dropdown form-control"></select>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  Semester You Entered Selected Program
                </td>
                <td>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
                    <?php echo Form::select('Semester', $semesters, null, ['class'=> 'reg_dropdown form-control']); ?>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  Where You In Cegep Previously?
                </td>
                <td>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
                    <input type="radio" id="option-1" class="mdl-radio__button" name="Cegep" value="1" checked>
                    <span class="mdl-radio__label">Yes</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
                    <input type="radio" id="option-2" class="mdl-radio__button" name="Cegep" value="0">
                    <span class="mdl-radio__label">Other (AP, French BAC, IB etc)</span>
                  </label>
                </td>
              </tr> */ ?>

            </table>

            <?php echo Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent new_user_submit']); ?>

          </div>
        </div>
      <?php echo Form::close(); ?>

    </div>

    <div class="mdl-cell mdl-cell--2-col"></div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
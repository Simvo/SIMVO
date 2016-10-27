<?php $__env->startSection('login'); ?>
<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--3-col"></div> <!-- Supplement for an Offset -->
  <div class="mdl-cell mdl-cell--6-col">
    <div class="mdl-card mdl-shadow--2dp auth_card">
      <div class="mdl-card__title mdl-card--expand">
        <h2 class="mdl-card__title-text">Login</h2>
      </div>
      <div class="mdl-card__supporting-text">
        <ul class="list-style-none">
          <?php foreach($errors->all() as $error): ?>
            <li><p class="submit_error"><?php echo e($error); ?></p></li>
          <?php endforeach; ?>
        </ul>
        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
          <div class="mdl-tabs__panel is-active" id="login-panel">
            <?php echo Form::open(['route' => 'login']); ?>

            <ul class="list-style-none">
              <li>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    <?php echo Form::label('email', 'E-mail', ['class'=> 'mdl-textfield__label']); ?>

                    <?php echo Form::email('email', old('email'), ['class'=> 'mdl-textfield__input']); ?>

                  </div>
              </li>
              <li>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                  <?php echo Form::label('password', 'Password', ['class'=> 'mdl-textfield__label']); ?>

                  <?php echo Form::password('password', ['class'=> 'mdl-textfield__input']); ?>

                </div>
              </li>

              <li>
                <?php echo Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']); ?>


              </li>
            </ul>
            <?php echo Form::close(); ?>

              <div class="center"><a href="<?php echo e(route('registration')); ?>">Not Signed up? Click here to Get Started!</a></div>
              <div class="center"><a href="<?php echo e(route('passwordEmailGet')); ?>">Forgot Password?</a></div>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
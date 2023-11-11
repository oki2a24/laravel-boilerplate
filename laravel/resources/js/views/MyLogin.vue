<template>
  <div class="container-fluid">
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
        <p class="h1 text-center">💡</p>
        <h1 class="text-center mb-4">ログイン</h1>

        <form>
          <div class="mb-3 row">
            <label for="inputEmail" class="col-sm-4 col-form-label">メールアドレス</label>
            <div class="col-sm-8">
              <input id="inputEmail" type="email" class="form-control" aria-describedby="emailHelp" required />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="inputPassword" class="col-sm-4 col-form-label">パスワード</label>
            <div class="col-sm-8">
              <input id="inputPassword" type="password" class="form-control" required />
            </div>
          </div>

          <div class="mb-3 form-check row justify-content-center align-items-center">
            <div class="col-sm-8 offset-sm-4">
              <input id="exampleCheck1" type="checkbox" class="form-check-input" />
              <label class="form-check-label" for="exampleCheck1">ログイン情報を記憶する</label>
            </div>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">ログイン</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

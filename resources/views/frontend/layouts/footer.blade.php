<!-- Footer -->
  <footer>
      <div class="row">
        <div class="col-12">
          <p class="footer-text">
          	@if(request()->segment(1) == "profile" || request()->segment(1) == "news-feed")
           	@else
           	© 2021 OyeChef • All Rights Reserved
           	@endif
          </p>
        </div>
      </div>
  </footer>
<!-- end footer -->
<footer id="footer">
    <div class="container">
        <div class="row mt-2">
            <div class="col-6 col-md-4">
                <ul class="list list-unstyled list-simple font-size-sm">
                    <li>
                        <a class="link-effect font-w600" href="{{ url('/') }}">Home</a>
                    </li>
                    <li>
                        <a class="link-effect font-w600" href="{{ url('/faq') }}">Frequently Asked Questions</a>
                    </li>
                    <li>
                        <a class="link-effect font-w600" href="{{ url('/contact-us') }}">Contact Us</a>
                    </li>
                </ul>
            </div>
            <div class="col-6 col-md-4">
                <h3 class="h5 font-w700">Explore</h3>
                <ul class="list list-unstyled list-simple font-size-sm">
                    <li>
                        <a class="link-effect font-w600" href="{{ url('/privacy') }}">Privacy Policy</a>
                    </li>
                    <li>
                        <a class="link-effect font-w600" href="{{ url('/terms-of-use') }}">Terms of Use</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="copyright text-right">&copy; {{ date('Y') }}. All Rights Reserved.</div>
    </div>
</footer>
    </main><!-- .site-main -->

    <footer class="site-footer">
        <p>&copy; <?php echo date( 'Y' ); ?> Igor Vukovic &mdash; Director of Photography</p>
    </footer>

</div><!-- .site-wrapper -->

<!-- VIDEO LIGHTBOX -->
<div class="lightbox-overlay" id="lightbox" role="dialog" aria-modal="true" aria-label="Video player">
    <div class="lightbox-inner">
        <button class="lightbox-close" id="lightbox-close" aria-label="Close video">&#215; Close</button>
        <div class="lightbox-ratio">
            <iframe id="lightbox-iframe" src="" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script src="https://player.vimeo.com/api/player.js"></script>
<?php wp_footer(); ?>
</body>
</html>

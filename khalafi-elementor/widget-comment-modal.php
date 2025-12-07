<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Comment_Modal_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'comment_modal_widget';
    }

    public function get_title() {
        return 'Comment Modal';
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function render() {
        ?>
        <button id="openCommentModal" class="comment-btn">ارسال نظر</button>

        <div id="commentModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span id="closeCommentModal" class="close">&times;</span>
                <?php comment_form(); ?>
            </div>
        </div>

        <style>
            .modal {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 99999;
            }
            .modal-content {
                background: #fff;
                width: 90%;
                max-width: 600px;
                padding: 20px;
                border-radius: 12px;
                position: relative;
            }
            .close {
                position: absolute;
                top: 10px;
                right: 20px;
                font-size: 26px;
                cursor: pointer;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('openCommentModal');
                const modal = document.getElementById('commentModal');
                const closeBtn = document.getElementById('closeCommentModal');

                btn.onclick = () => modal.style.display = 'flex';
                closeBtn.onclick = () => modal.style.display = 'none';
                window.onclick = e => { if (e.target === modal) modal.style.display = 'none'; };
            });
        </script>
        <?php
    }
}

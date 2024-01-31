<script>
import {Modal} from "bootstrap";

export default {
    props: {
        title: {
            type: String,
            default: ''
        },
        okOnly: {
            type: Boolean,
            default: false
        },
        centered: {
            type: Boolean,
            default: false
        },
        headerBgVariant: {
            type: String,
            default: null,
        },
        size: {
            type: String,
            default: null,
        },
        modalClass: {
            type: String,
            default: null,
        },
        disallowManualClose: {
            type: Boolean,
            default: false,
        },
        hidden: {
            type: Function,
        },
        shown: {
            type: Function,
        }
    },
    data() {
        return {
            modal_obj: null
        }
    },
    computed: {
        modal_main_extra_classes() {
            let classes = [];
            if (this.modalClass) {
                classes.push(this.modalClass);
            }
            return classes;
        },
        modal_dialog_extra_classes() {
            let classes = [];
            if (this.centered) {
                classes.push('modal-dialog-centered');
            }
            if (this.size) {
                classes.push('modal-' + this.size);
            }
            return classes;
        },
        modal_header_extra_classes() {
            let classes = [];
            if (this.headerBgVariant) {
                classes.push('bg-' + this.headerBgVariant);
            }
            return classes;
        },
        data_bs_backdrop() {
            if (this.disallowManualClose) {
                return 'static';
            }
            return null;
        },
        data_bs_keyboard() {
            if (this.disallowManualClose) {
                return 'false';
            }
            return null;
        }
    },
    mounted() {
        this.modal_obj = new Modal(this.$refs.modal_main);
        this.$refs.modal_main.addEventListener('hidden.bs.modal', () => {
            this.$emit('hidden');
        });
        this.$refs.modal_main.addEventListener('shown.bs.modal', () => {
            this.$emit('shown');
            this.autofocus();
        });
    },
    beforeUnmount() {
        this.modal_obj.dispose();
    },
    methods: {
        autofocus() {
            let autofocus_el = this.$refs.modal_main.querySelector('.modal-body [autofocus]');
            if (autofocus_el) {
                autofocus_el.focus();
            }
        },
        show() {
            this.modal_obj.show();
            // focus on the first autofocus element in the 'body' slot
            this.$nextTick(() => {

            });
        },
        hide() {
            this.modal_obj.hide();
        }
    }
}
</script>

<template>
    <div ref="modal_main" class="modal fade" :class="modal_main_extra_classes" tabindex="-1" :data-bs-backdrop="data_bs_backdrop" :data-bs-keyboard="data_bs_keyboard">
        <div class="modal-dialog" :class="modal_dialog_extra_classes">
            <div class="modal-content">
                <div class="modal-header" :class="modal_header_extra_classes">
                    <h5 class="modal-title">{{title}}<slot name="title"></slot></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><slot name="body"></slot></p>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        <!--
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                        -->
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

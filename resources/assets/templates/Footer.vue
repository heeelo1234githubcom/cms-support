    <template>

    <footer v-bind:class="{ 'px-footer p-t-0 px-footer-bottom' : $root.fixedMenu, 'px-footer p-t-0': !$root.fixedMenu }">
        <span class="text-muted">Copyright © 2017 {{ appName }}. All rights reserved.</span>
    </footer>

</template>

<script>
    export default {
        data () {
            return Application.languages.footer;
		}
    }
</script>

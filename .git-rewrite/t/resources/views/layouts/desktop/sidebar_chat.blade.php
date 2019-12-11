<div class="chat-sidebar-container" data-close-on-body-click="false">
    <div class="chat-sidebar">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#quick_sidebar_tab_1" class="nav-link active tab-icon" data-toggle="tab"> <i
                            class="material-icons">chat</i>Chat
                    <span class="badge badge-danger">4</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#quick_sidebar_tab_3" class="nav-link tab-icon" data-toggle="tab"> <i
                            class="material-icons">settings</i>
                    Settings
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Start User Chat -->
            <div class="tab-pane active chat-sidebar-chat in active show" role="tabpanel"
                 id="quick_sidebar_tab_1">
                <div class="chat-sidebar-list">
                    <div class="chat-sidebar-chat-users slimscroll-style" data-rail-color="#ddd"
                         data-wrapper-class="chat-sidebar-list">
                        <div class="chat-header">
                            <h5 class="list-heading">Online</h5>
                        </div>
                        <ul class="media-list list-items">
                            <li class="media"><img class="media-object" src="{{ asset('assets/img/prof/prof3.jpg') }}"
                                                   width="35" height="35" alt="...">
                                <i class="online dot"></i>
                                <div class="media-body">
                                    <h5 class="media-heading">John Deo</h5>
                                    <div class="media-heading-sub">Spine Surgeon</div>
                                </div>
                            </li>
                        </ul>
                        <div class="chat-header">
                            <h5 class="list-heading">Offline</h5>
                        </div>
                        <ul class="media-list list-items">
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-warning">4</span>
                                </div> <img class="media-object" src="{{ asset('assets/img/prof/prof6.jpg') }}"
                                            width="35" height="35" alt="...">
                                <i class="offline dot"></i>
                                <div class="media-body">
                                    <h5 class="media-heading">Jennifer Maklen</h5>
                                    <div class="media-heading-sub">Nurse</div>
                                    <div class="media-heading-small">Last seen 01:20 AM</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End User Chat -->
            <!-- Start Setting Panel -->
            <div class="tab-pane chat-sidebar-settings" role="tabpanel" id="quick_sidebar_tab_3">
                <div class="chat-sidebar-settings-list slimscroll-style">
                    <div class="chat-header">
                        <h5 class="list-heading">Layout Settings</h5>
                    </div>
                    <div class="chatpane inner-content ">
                        <div class="settings-list">
                            <div class="setting-item">
                                <div class="setting-text">Sidebar Position</div>
                                <div class="setting-set">
                                    <select
                                            class="sidebar-pos-option form-control input-inline input-sm input-small ">
                                        <option value="left" selected="selected">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Header</div>
                                <div class="setting-set">
                                    <select
                                            class="page-header-option form-control input-inline input-sm input-small ">
                                        <option value="fixed" selected="selected">Fixed</option>
                                        <option value="default">Default</option>
                                    </select>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Footer</div>
                                <div class="setting-set">
                                    <select
                                            class="page-footer-option form-control input-inline input-sm input-small ">
                                        <option value="fixed">Fixed</option>
                                        <option value="default" selected="selected">Default</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chat-header">
                            <h5 class="list-heading">Account Settings</h5>
                        </div>
                        <div class="settings-list">
                            <div class="setting-item">
                                <div class="setting-text">Notifications</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-1">
                                            <input type="checkbox" id="switch-1" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Show Online</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-7">
                                            <input type="checkbox" id="switch-7" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Status</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-2">
                                            <input type="checkbox" id="switch-2" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">2 Steps Verification</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-3">
                                            <input type="checkbox" id="switch-3" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-header">
                            <h5 class="list-heading">General Settings</h5>
                        </div>
                        <div class="settings-list">
                            <div class="setting-item">
                                <div class="setting-text">Location</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-4">
                                            <input type="checkbox" id="switch-4" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Save Histry</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-5">
                                            <input type="checkbox" id="switch-5" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Auto Updates</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                               for="switch-6">
                                            <input type="checkbox" id="switch-6" class="mdl-switch__input"
                                                   checked>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
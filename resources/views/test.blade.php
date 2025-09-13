<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Bar Troubleshooter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6f42c1;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --dark: #5a5c69;
            --light: #f8f9fc;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px;
        }
        
        .status-badge {
            font-size: 1rem;
            padding: 8px 15px;
            border-radius: 30px;
        }
        
        .code-block {
            background-color: #2d2d2d;
            color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }
        
        .keyword {
            color: #ff6b6b;
        }
        
        .function {
            color: #69db7c;
        }
        
        .variable {
            color: #74c0fc;
        }
        
        .string {
            color: #ff922b;
        }
        
        .comment {
            color: #868e96;
        }
        
        .btn-action {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 500;
        }
        
        .debug-info {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .troubleshooting-step {
            border-left: 4px solid var(--primary);
            padding-left: 15px;
            margin-bottom: 25px;
        }
        
        .checklist-item {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }
        
        .checklist-item.checked {
            background-color: #d4edda;
        }
        
        .tab-pane {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="text-white"><i class="fas fa-bug me-2"></i>Debug Bar Troubleshooter</h1>
            <p class="lead text-white">Why isn't the debug bar showing for Super Admin?</p>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Status</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <span>APP_DEBUG:</span>
                            <span class="badge bg-success status-badge">true</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <span>User Role:</span>
                            <span class="badge bg-primary status-badge">Super Admin</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <span>Debug Bar:</span>
                            <span class="badge bg-danger status-badge">Not Showing</span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Notice:</strong> Even though APP_DEBUG is set to true for Super Admin, the debug bar is not visible.
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-tools me-2"></i>Troubleshooting Steps</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">Configuration</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="middleware-tab" data-bs-toggle="tab" data-bs-target="#middleware" type="button" role="tab">Middleware</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="environment-tab" data-bs-toggle="tab" data-bs-target="#environment" type="button" role="tab">Environment</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cache-tab" data-bs-toggle="tab" data-bs-target="#cache" type="button" role="tab">Cache</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="config" role="tabpanel">
                        <h5 class="mt-3">Debug Bar Configuration Issues</h5>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-cog me-2 text-primary"></i>1. Check DebugBar Service Provider</h6>
                            <p>Ensure the DebugBar service provider is properly registered in <code>config/app.php</code>.</p>
                            <div class="code-block">
                                <span class="comment">// config/app.php</span><br>
                                'providers' => [<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Other service providers...</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;Barryvdh\Debugbar\ServiceProvider::class,<br>
                                ],<br><br>
                                'aliases' => [<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Other aliases...</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'Debugbar' => Barryvdh\Debugbar\Facades\Debugbar::class,<br>
                                ],
                            </div>
                        </div>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-filter me-2 text-primary"></i>2. Check DebugBar Environment Configuration</h6>
                            <p>DebugBar might be disabled in certain environments. Check <code>config/debugbar.php</code>:</p>
                            <div class="code-block">
                                <span class="comment">// config/debugbar.php</span><br>
                                'enabled' => env('DEBUGBAR_ENABLED', null),<br>
                                <br>
                                <span class="comment">// If null, it will determine based on APP_DEBUG</span><br>
                                <span class="comment">// Make sure there's no override in your .env file</span>
                            </div>
                        </div>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-user-shield me-2 text-primary"></i>3. Check DebugBar Authorization</h6>
                            <p>DebugBar might have its own authorization logic. Check <code>config/debugbar.php</code>:</p>
                            <div class="code-block">
                                <span class="comment">// config/debugbar.php</span><br>
                                'middleware' => [<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'web',<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;Barryvdh\Debugbar\Middleware\Debugbar::class,<br>
                                ],<br><br>
                                <span class="comment">// Ensure there's no IP restriction</span><br>
                                'allowed_ips' => ['127.0.0.1', '::1'], <span class="comment">// Remove or adjust if needed</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="middleware" role="tabpanel">
                        <h5 class="mt-3">Middleware Configuration</h5>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-code me-2 text-primary"></i>1. Verify Your Middleware Code</h6>
                            <p>Your middleware should look like this:</p>
                            <div class="code-block">
                                <span class="keyword">public function</span> <span class="function">handle</span>(Request $request, Closure $next): Response<br>
                                {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Check if user is authenticated first</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">if</span> (<span class="variable">Auth::check</span>() && <span class="variable">Auth::user</span>()-><span class="function">hasRole</span>(<span class="string">'Super Admin'</span>)) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">config</span>([<span class="string">'app.debug'</span> => <span class="keyword">true</span>]);<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;} <span class="keyword">else</span> {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">config</span>([<span class="string">'app.debug'</span> => <span class="keyword">false</span>]);<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Also enable debugbar specifically</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">if</span> (<span class="variable">Auth::check</span>() && <span class="variable">Auth::user</span>()-><span class="function">hasRole</span>(<span class="string">'Super Admin'</span>)) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">config</span>([<span class="string">'debugbar.enabled'</span> => <span class="keyword">true</span>]);<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;} <span class="keyword">else</span> {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">config</span>([<span class="string">'debugbar.enabled'</span> => <span class="keyword">false</span>]);<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> <span class="variable">$next</span>($request);<br>
                                }
                            </div>
                        </div>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-list-ol me-2 text-primary"></i>2. Check Middleware Execution Order</h6>
                            <p>Ensure your middleware runs before the DebugBar middleware. In <code>app/Http/Kernel.php</code>:</p>
                            <div class="code-block">
                                <span class="keyword">protected</span> $middleware = [<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Other middleware...</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;\App\Http\Middleware\DebugForSuperAdmin::class,<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;\Barryvdh\Debugbar\Middleware\Debugbar::class,<br>
                                ];
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="environment" role="tabpanel">
                        <h5 class="mt-3">Environment Configuration</h5>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-file-alt me-2 text-primary"></i>1. Check Your .env File</h6>
                            <p>Ensure your .env file has the correct settings:</p>
                            <div class="code-block">
                                APP_DEBUG=false<br>
                                DEBUGBAR_ENABLED=null <span class="comment"># Let it be controlled by code</span>
                            </div>
                        </div>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-server me-2 text-primary"></i>2. Environment-Based Configuration</h6>
                            <p>DebugBar might be disabled in production environment. Check <code>config/debugbar.php</code>:</p>
                            <div class="code-block">
                                <span class="keyword">public function</span> <span class="function">isEnabled</span>()<br>
                                {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $this->app->make('config')->get('debugbar.enabled') ??<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->app->make('config')->get('app.debug') && !$this->app->environment('production');<br>
                                }
                            </div>
                            <p>If you're in production environment, you may need to override this behavior.</p>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="cache" role="tabpanel">
                        <h5 class="mt-3">Cache Issues</h5>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-broom me-2 text-primary"></i>1. Clear Configuration Cache</h6>
                            <p>Configuration might be cached. Run these commands:</p>
                            <div class="code-block">
                                php artisan config:clear<br>
                                php artisan cache:clear<br>
                                composer dump-autoload
                            </div>
                        </div>
                        
                        <div class="troubleshooting-step">
                            <h6><i class="fas fa-sync-alt me-2 text-primary"></i>2. Restart Web Server</h6>
                            <p>Sometimes OPcache or other caching mechanisms need to be restarted:</p>
                            <div class="code-block">
                                <span class="comment"># For Apache:</span><br>
                                sudo service apache2 restart<br><br>
                                <span class="comment"># For Nginx with PHP-FPM:</span><br>
                                sudo service nginx restart<br>
                                sudo service php-fpm restart
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Checklist</h5>
                    <div class="checklist-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1">
                            <label class="form-check-label" for="check1">
                                DebugBar service provider is registered in config/app.php
                            </label>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check2">
                            <label class="form-check-label" for="check2">
                                DebugBar middleware is properly ordered in Kernel.php
                            </label>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check3">
                            <label class="form-check-label" for="check3">
                                Configuration cache is cleared (config:clear, cache:clear)
                            </label>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check4">
                            <label class="form-check-label" for="check4">
                                .env file has APP_DEBUG=false and DEBUGBAR_ENABLED=null
                            </label>
                        </div>
                    </div>
                    <div class="checklist-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check5">
                            <label class="form-check-label" for="check5">
                                DebugBar config doesn't have IP restrictions preventing access
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-code me-2"></i>Enhanced Middleware Solution</h4>
            </div>
            <div class="card-body">
                <p>Try this enhanced version of your middleware that specifically handles DebugBar configuration:</p>
                
                <div class="code-block">
                    <span class="keyword">public function</span> <span class="function">handle</span>(Request $request, Closure $next): Response<br>
                    {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Check if user is authenticated and is Super Admin</span><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;$isSuperAdmin = <span class="variable">Auth::check</span>() && <span class="variable">Auth::user</span>()-><span class="function">hasRole</span>(<span class="string">'Super Admin'</span>);<br>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Set APP_DEBUG based on role</span><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">config</span>([<span class="string">'app.debug'</span> => $isSuperAdmin]);<br>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// Also explicitly enable/disable DebugBar</span><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">config</span>([<span class="string">'debugbar.enabled'</span> => $isSuperAdmin]);<br>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">// For DebugBar v3.13+, also check if the provider is loaded</span><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">if</span> ($isSuperAdmin && class_exists(<span class="string">'Barryvdh\Debugbar\LaravelDebugbar'</span>)) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="variable">app</span>()-><span class="function">register</span>(<span class="string">'Barryvdh\Debugbar\ServiceProvider'</span>);<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> <span class="variable">$next</span>($request);<br>
                    }
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add interactivity to checklist
            const checkboxes = document.querySelectorAll('.form-check-input');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        this.parentElement.parentElement.classList.add('checked');
                    } else {
                        this.parentElement.parentElement.classList.remove('checked');
                    }
                });
            });
        });
    </script>
</body>
</html>
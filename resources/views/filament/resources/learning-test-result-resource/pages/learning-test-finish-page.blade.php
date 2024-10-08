<style>
    #confetti {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    x-filament::section {
        position: relative;
        z-index: 1;
    }
</style>

<x-filament-panels::page>
    @if ($record->is_passed)
        <canvas id="confetti"></canvas>
    @endif
    <x-filament::section>
        <div class="grid min-h-full place-items-center bg-white px-6 py-12 md:py-32">
            <div class="text-center items-center">
                <div class="flex justify-center items-center">
                    <p class="text-base font-semibold">
                        <x-tabler-certificate
                            class="{{ $record->is_passed ? 'text-blue-300' : 'text-red-300' }} h-40 w-40" />
                    </p>
                </div>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                    @if ($record->is_passed)
                        {{ __('learning/learningTestResult.custom.congratulations') }}
                    @else
                        {{ __('learning/learningTestResult.custom.did_not_pass') }}
                    @endif
                </h1>
                <p class="mt-6 text-base leading-7 text-gray-600">
                    {{ $record->is_passed ? __('learning/learningTestResult.custom.you_passed') : '' }}
                    {{ __('learning/learningTestResult.custom.thank_you_for_completing_the_test') }}
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('filament.app.resources.learning-test-results.do-test', ['record' => $record->id, 'question' => 1, 'viewTest' => 1]) }}"
                        class="mt-6 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        <div class="flex">
                            <x-tabler-checkbox class="text-white me-1" />
                            {{ __('learning/learningTestResult.custom.test_results') }}
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>

@if ($record->is_passed)
    <script>
        let W = window.innerWidth;
        let H = window.innerHeight;
        const canvas = document.getElementById("confetti");
        const context = canvas.getContext("2d");
        const maxConfettis = W <= 800 ? 50 : 150;
        const particles = [];
        let startTime = null; // To track when the confetti started
        const opacityDecreaseStart = 5000; // 4 seconds

        const possibleColors = [
            "Gold",
            "Gold",
            "Gold",
            "Gold",
            "Gold",
            "OliveDrab",
            "Pink",
            "LightBlue",
            "Violet",
            "PaleGreen",
            "SteelBlue",
            "Chocolate",
            "Crimson"
        ];

        function randomFromTo(from, to) {
            return Math.floor(Math.random() * (to - from + 1) + from);
        }

        function confettiParticle() {
            this.x = Math.random() * W; // x
            this.y = Math.random() * H - H; // y
            this.r = randomFromTo(11, 33); // radius
            this.d = Math.random() * maxConfettis + 11;
            this.color =
                possibleColors[Math.floor(Math.random() * possibleColors.length)];
            this.tilt = Math.floor(Math.random() * 33) - 11;
            this.tiltAngleIncremental = Math.random() * 0.07 + 0.05;
            this.tiltAngle = 0;
            this.opacity = 1; // Initial opacity

            this.draw = function() {
                context.beginPath();
                context.lineWidth = this.r / 2;
                context.strokeStyle = this.color;
                context.globalAlpha = this.opacity; // Set opacity for this particle
                context.moveTo(this.x + this.tilt + this.r / 3, this.y);
                context.lineTo(this.x + this.tilt, this.y + this.tilt + this.r / 5);
                return context.stroke();
            };

            // Function to gradually reduce the opacity
            this.reduceOpacity = function(elapsedTime) {
                if (elapsedTime >= opacityDecreaseStart) {
                    // Gradually decrease opacity after 4 seconds
                    const timeSinceStart = elapsedTime - opacityDecreaseStart;
                    this.opacity = Math.max(1 - timeSinceStart / 2000, 0); // Fade out over 2 seconds
                }
            };
        }

        function Draw(timestamp) {
            if (!startTime) {
                startTime = timestamp; // Track the start time
            }

            const elapsedTime = timestamp - startTime; // Calculate elapsed time

            // Continue the animation loop
            requestAnimationFrame(Draw);

            context.clearRect(0, 0, W, window.innerHeight);

            for (var i = 0; i < maxConfettis; i++) {
                particles[i].reduceOpacity(elapsedTime); // Update opacity
                particles[i].draw(); // Draw the particle
            }

            let particle = {};
            for (var i = 0; i < maxConfettis; i++) {
                particle = particles[i];

                particle.tiltAngle += particle.tiltAngleIncremental;
                particle.y += (Math.cos(particle.d) + 3 + particle.r / 2) / 2;
                particle.tilt = Math.sin(particle.tiltAngle - i / 3) * 15;

                // If a confetti has fluttered out of view,
                // bring it back to above the viewport and let it re-fall.
                if (particle.x > W + 30 || particle.x < -30 || particle.y > H) {
                    particle.x = Math.random() * W;
                    particle.y = -30;
                    particle.tilt = Math.floor(Math.random() * 10) - 20;
                }
            }
        }

        window.addEventListener(
            "resize",
            function() {
                W = window.innerWidth;
                H = window.innerHeight;
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            },
            false
        );

        // Push new confetti objects to `particles[]`
        for (var i = 0; i < maxConfettis; i++) {
            particles.push(new confettiParticle());
        }

        // Initialize
        canvas.width = W;
        canvas.height = H;

        requestAnimationFrame(Draw);
    </script>
@endif

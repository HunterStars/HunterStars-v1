<section id="lists">
	<details>
		<summary>lists</summary>
		<style>
            #lists details>div {
                border-bottom: 2px solid black;
            }

            #lists details>div>div {
                display: inline-block;
                width: 49%;
            }

            #lists details>div>div:first-child {
                border-right: 2px solid red;
            }

            #lists details>div>div:last-child {
                padding-left: 20px;
            }

		</style>

		<details >
			<summary>lists simples</summary>
            <h4>list simple (No clickeable):</h4>
            <ul class="list">
                <li class="item">
                    Elemento 1
                </li>
                <li class="item">
                    Elemento 2
                </li>
                <li class="item">
                    Elemento 3
                </li>
            </ul>
			<div>
				<div>
					<h4>list simple (No clickeable):</h4>
					<ul class="list">
						<li class="item">
							<span>Elemento 1</span>
						</li>
						<li class="item">
							<span class="text">Elemento 2</span>
						</li>
						<li class="item">
							<span>Elemento 3</span>
						</li>
					</ul>
				</div>
				<div>
					<h4>list simple con icono (No clickeable):</h4>
					<ul class="list">
						<li>
							<div class="item">
								<i class="m-icons">home</i>
								<span>Elemento 1</span>
							</div>
						</li>
						<li>
							<div class="item">
								<i class="m-icons">home</i>
								<span class="text">Elemento 2</span>
							</div>
						</li>
						<li>
							<div class="item">
								<i class="m-icons">home</i>
								<span>Elemento 3</span>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div>
				<div>
					<h4>list simple:</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<span>Elemento 1</span>
							</a>
						</li>
						<li>
							<a href="#2" class="item">
								<span>Elemento 2</span>
							</a>
						</li>
						<li>
							<a href="#3" class="item">
								<span>Elemento 3</span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
								<span>Elemento 4</span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
								<span class="text">Elemento 5</span>
							</a>
						</li>
					</ul>
				</div>
				<div>
					<h4>list simple (Elemento desactivado):</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<span class="text">Elemento 1</span>
							</a>
						</li>
						<li>
							<a href="#2" class="item">
								<span>Elemento 2</span>
							</a>
						</li>
						<li>
							<a href="#3" class="item" disabled>
								<span>Elemento 3</span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
								<span>Elemento 4</span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
								<span>Elemento 5</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div>
				<div>
					<h4>list simple con icono:</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<i class="m-icons">home</i>
								<span class="text">Elemento 1</span>
							</a>
						</li>
						<li>
							<a href="#2" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 2</span>
							</a>
						</li>
						<li>
							<a href="#3" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 3</span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 4</span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 5</span>
							</a>
						</li>
					</ul>
				</div>
				<div>
					<h4>list simple con icono (Elemento desactivado):</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 1</span>
							</a>
						</li>
						<li>
							<a href="#2" class="item" disabled>
								<i class="m-icons">home</i>
								<span>Elemento 2</span>
							</a>
						</li>
						<li>
							<a href="#3" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 3</span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 4</span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 5</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div>
				<div>
					<h4>list simple con subtitle:</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<span>Elemento 1</span>
							</a>
						</li>
						<li class="subtitle">subtitle 1</li>
						<li>
							<a href="#2" class="item">
								<span>Elemento 2</span>
							</a>
						</li>
						<li>
							<a href="#3" class="item">
								<span>Elemento 3</span>
							</a>
						</li>
						<li class="subtitle">subtitle 2</li>
						<li>
							<a href="#4" class="item">
								<span>Elemento 4</span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
								<span>Elemento 5</span>
							</a>
						</li>
					</ul>
				</div>
				<div>
					<h4>list simple con elemento desactivado:</h4>
					<ul class="list">
						<li class="subtitle">subtitle 1</li>
						<li>
							<a href="#1" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 1</span>
							</a>
						</li>
						<li class="subtitle">subtitle 2</li>
						<li>
							<a href="#2" class="item">
								<i class="m-icons">home</i>
								<span>Elemento 2</span>
							</a>
						</li>
						<li>
							<a href="#3" class="item">
								<i class="m-icons">home</i>
								<span class="text">Elemento 3</span>
							</a>
						</li>
						<li>
							<a href="#4" class="item" disabled>
								<i class="m-icons">home</i>
								<span class="text">Elemento 4</span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
								<i class="m-icons">home</i>
								<span class="text">Elemento 5</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</details>
		<details>
			<summary>lists con detalles</summary>
			<div>
				<div>
					<h4>list con detalles (Sin icono):</h4>
					<ul class="list">
						<li class="subtitle">subtitle 1</li>
						<li>
							<a href="#1" class="item">
                                    <span class="text">
                                        <span>Elemento 1</span>
                                        <span>Sub elemento 1</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#2" class="item">
                                    <span class="text">
                                        <span>Elemento 2</span>
                                        <span>Sub elemento 2</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#3" class="item" disabled>
                                    <span class="text">
                                        <span>Elemento 3</span>
                                        <span>Sub elemento 3</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
                                    <span class="text">
                                        <span>Elemento 4</span>
                                        <span>Sub elemento 4</span>
                                    </span>
							</a>
						</li>
					</ul>
				</div>
				<div>
					<h4>list con detalles (Con icono):</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 1</span>
                                        <span>Sub elemento 1</span>
                                    </span>
							</a>
						</li>
						<li class="subtitle">subtitle 1</li>
						<li>
							<a href="#2" class="item" disabled>
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 2</span>
                                        <span>Sub elemento 2</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#3" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 3</span>
                                        <span>Sub elemento 3</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 4</span>
                                        <span>Sub elemento 4</span>
                                    </span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</details>
		<details>
			<summary>lists con Sublists</summary>
			<div>
				<div>
					<h4>list con sublists (Sin icono):</h4>
					<ul class="list">
						<li class="subtitle">subtitle 1</li>
						<li>
							<a href="#1" class="item">
                                    <span class="text">
                                        <span>Elemento 1</span>
                                        <span>Sub elemento 1</span>
                                    </span>
							</a>
						</li>
						<li open="">
							<a href="#2" class="item">
                                    <span class="text">
                                        <span>Elemento 2</span>
                                        <span>Sub elemento 2</span>
                                    </span>
							</a>
							<ul>
								<li>
									<a href="#5" class="item">
                                            <span class="text">
                                                <span>Elemento 1</span>
                                                <span>Sub elemento 1</span>
                                            </span>
									</a>
								</li>
								<li>
									<a href="#6" class="item">
                                            <span class="text">
                                                <span>Elemento 2</span>
                                                <span>Sub elemento 2</span>
                                            </span>
									</a>
									<ul>
										<li>
											<a href="#7" class="item">
                                                    <span class="text">
                                                        <span>Elemento 1</span>
                                                        <span>Sub elemento 1</span>
                                                    </span>
											</a>
										</li>
										<li>
											<a href="#8" class="item">
                                                    <span class="text">
                                                        <span>Elemento 1</span>
                                                        <span>Sub elemento 1</span>
                                                    </span>
											</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li>
							<a href="#3" class="item" disabled>
                                    <span class="text">
                                        <span>Elemento 3</span>
                                        <span>Sub elemento 3</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
                                    <span class="text">
                                        <span>Elemento 4</span>
                                        <span>Sub elemento 4</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#5" class="item">
                                    <span class="text">
                                        <span>Elemento 5</span>
                                        <span>Sub elemento 5</span>
                                    </span>
							</a>
						</li>
					</ul>
				</div>
				<div>
					<h4>list con sublists:</h4>
					<ul class="list">
						<li>
							<a href="#1" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 1</span>
                                        <span>Sub Elemento 1</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#2" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 2</span>
                                        <span>
                                            <i class="m-icons">import_contacts</i>
                                            <span>Sub Elemento 2</span>
                                        </span>
                                    </span>
							</a>
						</li>
						<li class="subtitle">subtitle 1</li>
						<li>
							<a href="#2" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 3</span>
                                        <span>Sub elemento 3</span>
                                    </span>
							</a>
							<ul>
								<li>
									<a href="#5" class="item">
										<i class="m-icons">home</i>
										<span class="text">
                                                <span>Elemento 1</span>
                                                <span>Sub elemento 1</span>
                                            </span>
									</a>
								</li>
								<li>
									<a href="#6" class="item">
										<i class="m-icons">home</i>
										<span class="text">
                                                <span>Elemento 2</span>
                                                <span>Sub elemento 2</span>
                                            </span>
									</a>
									<ul>
										<li>
											<a href="#7" class="item">
												<i class="m-icons">home</i>
												<span class="text">
                                                        <span>Elemento 1</span>
                                                        <span>Sub elemento 1</span>
                                                    </span>
											</a>
										</li>
										<li>
											<a href="#8" class="item">
												<i class="m-icons">home</i>
												<span class="text">
                                                        <span>Elemento 1</span>
                                                        <span>Sub elemento 1</span>
                                                    </span>
											</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li>
							<a href="#3" class="item" disabled>
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 4</span>
                                        <span>Sub elemento 4</span>
                                    </span>
							</a>
						</li>
						<li>
							<a href="#4" class="item">
								<i class="m-icons">home</i>
								<span class="text">
                                        <span>Elemento 5</span>
                                        <span>Sub elemento 5</span>
                                    </span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</details>
		<details>
			<summary>lists con botones</summary>
			<div>
				<div>
					<h4>list simple (Sin icono):</h4>
					<ul class="list">
						<li class="subtitle"><h6>subtitle 1</h6></li>
						<li>
							<article class="item">
								<a href="#1">
									<span class="text">Elemento 1</span>
								</a>
								<button class="Menu btn-icon" pressed="false">
									<i class="m-icons">favorite</i>
									<i class="m-icons">favorite_border</i>
								</button>
								<button class="btn-icon m-icons">
									visibility_off
								</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#2">
									<span class="text">Elemento 2</span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#3">
									<span class="text">Elemento 3</span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li class="subtitle"><h6>subtitle 2</h6></li>
						<li>
							<article class="item">
								<a href="#1">
                                        <span class="text">
                                            <span>Elemento 4</span>
                                            <span>Sub Elemento 4</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#1">
                                        <span class="text">
                                            <span>Elemento 5</span>
                                            <span>Sub Elemento 5</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#1">
                                        <span class="text">
                                            <span>Elemento 6</span>
                                            <span>Sub Elemento 6</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
					</ul>
				</div>
				<div>
					<h4>list simple (Con icono):</h4>
					<ul class="list">
						<li class="subtitle"><h6>subtitle 1</h6></li>
						<li>
							<article class="item">
								<a href="#1">
									<i class="m-icons">home</i>
									<span class="text">Elemento 1</span>
								</a>
								<button class="Menu btn-icon" pressed="false">
									<i class="m-icons">favorite</i>
									<i class="m-icons">favorite_border</i>
								</button>
								<button class="btn-icon m-icons">
									visibility_off
								</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#2">
									<i class="m-icons">home</i>
									<span class="text">Elemento 2</span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#3">
									<i class="m-icons">home</i>
									<span class="text">Elemento 3</span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li class="subtitle"><h6>subtitle 2</h6></li>
						<li>
							<article class="item">
								<a href="#1">
									<i class="m-icons">home</i>
									<span class="text">
                                            <span>Elemento 4</span>
                                            <span>Sub Elemento 4</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#1">
									<i class="m-icons">home</i>
									<span class="text">
                                            <span>Elemento 5</span>
                                            <span>Sub Elemento 5</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#1">
									<i class="m-icons">home</i>
									<span class="text">
                                            <span>Elemento 6</span>
                                            <span>Sub Elemento 6</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
					</ul>
				</div>
			</div>
			<div>
				<div>
					<h4>list simple (Sin imagen):</h4>
					<ul class="list">
						<li class="subtitle"><h6>subtitle 1</h6></li>
						<li>
							<article class="item">
								<a href="#1">
									<figure>
										<img src="Img/04.png">
									</figure>
									<span class="text">Elemento 1</span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#2">
									<figure>
										<img src="Img/04.png">
									</figure>
									<span class="text">
                                            <span>
                                              <i class="m-icons" style="color: red;">fiber_new</i>
                                               <span>Elemento 2</span>
                                            </span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#3">
									<figure>
										<img src="Img/04.png">
									</figure>
									<span class="text">Elemento 3</span>
								</a>
								<button class="btn-icon m-icons">visibility_off</button>
								<button class="Menu btn-icon" pressed="false">
									<i class="m-icons">favorite</i>
									<i class="m-icons">favorite_border</i>
								</button>
							</article>
						</li>
						<li class="subtitle"><h6>subtitle 2</h6></li>
						<li>
							<article class="item">
								<a href="#4">
									<figure>
										<img src="Img/04.png" alt="Icon">
									</figure>
									<span class="text">
                                            <span>Elemento 4</span>
                                            <span>Sub Elemento 4</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#5">
									<figure>
										<img src="Img/04.png">
									</figure>
									<span class="text">
                                            <span>Elemento 5</span>
                                            <span>Sub Elemento 5</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#6">
									<figure>
										<img src="Img/04.png">
									</figure>
									<span class="text">
                                            <span>Elemento 6</span>
                                            <span>Sub Elemento 6</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">visibility_off</button>
								<button class="Menu btn-icon" pressed="false">
									<i class="m-icons">favorite</i>
									<i class="m-icons">favorite_border</i>
								</button>
							</article>
						</li>
					</ul>
				</div>
				<div>
					<h4>list simple (Con imagen):</h4>
					<ul class="list">
						<li class="subtitle"><h6>subtitle 1</h6></li>
						<li>
							<article class="item">
								<a href="#1">
									<figure>
										<img src="/files/img/basic/Cap.png">
									</figure>
									<span class="text">Elemento 1</span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#2">
									<figure>
										<img src="/files/img/basic/Cap.png">
									</figure>
									<span class="text">
                                            <span>
                                              <i class="m-icons" style="color: red;">fiber_new</i>
                                               <span>Elemento 2</span>
                                            </span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#3">
									<figure>
										<img src="/files/img/basic/Cap.png">
									</figure>
									<span class="text">Elemento 3</span>
								</a>
								<button class="btn-icon m-icons">visibility_off</button>
								<button class="Menu btn-icon" pressed="false">
									<i class="m-icons">favorite</i>
									<i class="m-icons">favorite_border</i>
								</button>
							</article>
						</li>
						<li class="subtitle"><h6>subtitle 2</h6></li>
						<li>
							<article class="item">
								<a href="#4">
									<figure>
										<img src="/files/img/basic/Cap.png">
									</figure>
									<span class="text">
                                            <span>Elemento 4</span>
                                            <span>Sub Elemento 4</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">cloud_download</button>
								<button class="btn-icon m-icons">share</button>
							</article>
						</li>
						<li>
							<article class="item">
								<a href="#5">
									<figure>
										<img src="/files/img/basic/Cap.png?h=80">
									</figure>
									<span class="text">
                                            <span>Elemento 5</span>
                                            <span>Sub Elemento 5</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">fiber_new</button>
								<button class="btn-icon m-icons">done</button>
							</article>
						</li>
						<li>
							<article class="item" disabled>
								<a href="#6">
									<figure>
										<img src="/files/img/basic/Cap.png">
									</figure>
									<span class="text">
                                            <span>Elemento 6</span>
                                            <span>Sub Elemento 6</span>
                                        </span>
								</a>
								<button class="btn-icon m-icons">visibility_off</button>
								<button class="Menu btn-icon" pressed="false">
									<i class="m-icons">favorite</i>
									<i class="m-icons">favorite_border</i>
								</button>
							</article>
						</li>
					</ul>
				</div>
			</div>
		</details>
	</details>
</section>
@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .email-workspace{display:grid;grid-template-columns:minmax(0,1.15fr) minmax(360px,.85fr);gap:1rem;align-items:start}
    .editor-tools{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem}
    .tool-chip{border:1px solid #d1d3e2;background:#fff;color:#4e73df;border-radius:999px;padding:.42rem .75rem;font-size:.78rem;font-weight:800;cursor:pointer}
    .tool-chip:hover{background:#f8f9fc}
    .preview-frame-wrap{background:#f8f9fc;border:1px solid #e3e6f0;border-radius:.65rem;padding:1rem}
    .preview-toolbar{display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:.75rem}
    .preview-toggle{display:inline-flex;border:1px solid #d1d3e2;border-radius:999px;overflow:hidden;background:#fff}
    .preview-toggle button{border:0;background:transparent;color:#5a5c69;padding:.35rem .7rem;font-size:.75rem;font-weight:800}
    .preview-toggle button.active{background:#4e73df;color:#fff}
    .email-preview{display:block;width:100%;height:620px;margin:0 auto;background:#fff;border:1px solid #d1d3e2;border-radius:.45rem}
    .email-preview.mobile{max-width:390px}
    .note-editor.note-frame{border-color:#d1d3e2;border-radius:.55rem;overflow:hidden}
    .note-toolbar{background:#f8f9fc}
    .template-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem}
    .template-card{border:1px solid #e3e6f0;background:#fff;border-radius:.55rem;padding:.85rem;text-align:left;cursor:pointer}
    .template-card strong{display:block;color:#4e73df;margin-bottom:.3rem}
    .template-card span{display:block;color:#858796;font-size:.82rem}
    .template-card:hover{border-color:#4e73df;box-shadow:0 .15rem 1rem rgba(78,115,223,.12)}
    @media (max-width:1100px){.email-workspace{grid-template-columns:1fr}.email-preview{height:520px}}
</style>
@endpush

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $campaign->exists ? 'Editar' : 'Nova' }} campanha</h1>
        <p>Configure o envio, edite o conteudo visualmente e visualize antes de salvar.</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.campaigns.index') }}">Voltar</a>
    </div>
</div>

<form class="form-shell" method="post" action="{{ $campaign->exists ? route('email-marketing.campaigns.update',$campaign) : route('email-marketing.campaigns.store') }}">
    @csrf
    @if($campaign->exists) @method('put') @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Configuracao</h2>
            <p>Defina nome, provedor e status inicial.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" value="{{ old('name',$campaign->name) }}" required autofocus>
                </div>
                <div class="form-field">
                    <label for="provider_id">Provedor</label>
                    <select id="provider_id" name="provider_id" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" @selected(old('provider_id',$campaign->provider_id)==$provider->id)>{{ $provider->name }} ({{ $provider->type }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        @foreach(['draft','scheduled','paused','canceled'] as $s)
                            <option @selected(old('status',$campaign->status ?: 'draft')===$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="scheduled_at">Agendada para</label>
                    <input id="scheduled_at" type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Conteudo</h2>
            <p>Use o editor visual para formatar textos, links, imagens e chamadas.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid" style="margin-bottom:1rem">
                <div class="form-field">
                    <label for="subject">Assunto</label>
                    <input id="subject" name="subject" value="{{ old('subject',$campaign->subject) }}" required>
                </div>
                <div class="form-field">
                    <label for="preheader">Preheader</label>
                    <input id="preheader" name="preheader" value="{{ old('preheader',$campaign->preheader) }}">
                    <span class="form-help">Texto curto exibido em alguns clientes de e-mail.</span>
                </div>
            </div>

            <div class="form-card" style="box-shadow:none;border:1px solid #e3e6f0">
                <div class="form-card-header">
                    <h2>Modelos</h2>
                    <p>Comece por uma estrutura pronta e edite o conteudo.</p>
                </div>
                <div class="form-card-body">
                    <div class="template-grid">
                        <button type="button" class="template-card" data-template="announcement"><strong>Comunicado</strong><span>Mensagem objetiva com botao principal.</span></button>
                        <button type="button" class="template-card" data-template="newsletter"><strong>Newsletter</strong><span>Blocos para novidades, links e resumo.</span></button>
                        <button type="button" class="template-card" data-template="invite"><strong>Convite</strong><span>Estrutura para evento ou chamada especial.</span></button>
                    </div>
                </div>
            </div>

            <div class="email-workspace">
                <div>
                    <label for="html_body" class="form-label">Editor visual</label>
                    <div class="editor-tools">
                        <button type="button" class="tool-chip" data-variable="name">Nome</button>
                        <button type="button" class="tool-chip" data-variable="email">E-mail</button>
                        <button type="button" class="tool-chip" data-variable="unsubscribe_url">Descadastro</button>
                    </div>
                    <textarea id="html_body" name="html_body" required>{{ old('html_body',$campaign->html_body) }}</textarea>
                    <span class="form-help">As imagens enviadas pelo editor ficam em public/email-images.</span>

                    <div class="form-field full" style="margin-top:1rem">
                        <label for="text_body">Texto puro</label>
                        <textarea id="text_body" name="text_body">{{ old('text_body',$campaign->text_body) }}</textarea>
                    </div>
                </div>

                <div class="preview-frame-wrap">
                    <div class="preview-toolbar">
                        <strong>Visualizacao</strong>
                        <div class="preview-toggle">
                            <button type="button" class="active" data-preview-size="desktop">Desktop</button>
                            <button type="button" data-preview-size="mobile">Mobile</button>
                        </div>
                    </div>
                    <iframe id="emailPreview" class="email-preview" title="Visualizacao do e-mail"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Listas</h2>
            <p>Escolha os grupos que receberao esta campanha.</p>
        </div>
        <div class="form-card-body">
            <div class="form-field full">
                <label for="lists">Listas</label>
                <select id="lists" name="lists[]" multiple size="8" required>
                    @foreach($lists as $list)
                        <option value="{{ $list->id }}" @selected(in_array($list->id, old('lists', $campaign->lists->pluck('id')->all())))>{{ $list->name }}</option>
                    @endforeach
                </select>
                <span class="form-help">Segure Ctrl para selecionar varias listas.</span>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn">Salvar</button>
        <a class="btn secondary" href="{{ route('email-marketing.campaigns.index') }}">Cancelar</a>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
    window.emailEditorConfig = {
        uploadUrl: @json(route('email-marketing.campaigns.upload-image')),
        csrf: @json(csrf_token())
    };
</script>
@verbatim
<script>
    const templates = {
        announcement: `
            <div style="margin:0;background:#f4f6fb;padding:32px 12px;font-family:Arial,sans-serif;color:#263238">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:14px;overflow:hidden">
                    <tr>
                        <td style="background:#224abe;color:#ffffff;padding:34px 38px">
                            <div style="font-size:13px;text-transform:uppercase;letter-spacing:1px;opacity:.85">Comunicado</div>
                            <h1 style="margin:10px 0 0;font-size:30px;line-height:1.2">Titulo da campanha</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 38px">
                            <p style="font-size:17px;line-height:1.7;margin:0 0 18px">Ola {{name}}, escreva aqui a mensagem principal do seu comunicado.</p>
                            <p style="font-size:15px;line-height:1.7;margin:0 0 26px;color:#5f6b7a">Use este espaco para explicar o beneficio, contexto ou proximo passo.</p>
                            <a href="https://salvaimerainha.org.br" style="display:inline-block;background:#4e73df;color:#ffffff;text-decoration:none;padding:13px 22px;border-radius:8px;font-weight:bold">Acessar</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 38px;background:#f8f9fc;color:#7b8794;font-size:12px">Salve Rainha</td>
                    </tr>
                </table>
            </div>`,
        newsletter: `
            <div style="margin:0;background:#eef2f7;padding:28px 12px;font-family:Arial,sans-serif;color:#263238">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:720px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden">
                    <tr>
                        <td style="padding:30px 34px;border-bottom:1px solid #e5e7eb">
                            <h1 style="margin:0;font-size:28px;color:#224abe">Newsletter</h1>
                            <p style="margin:8px 0 0;color:#667085">Resumo das principais novidades.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 34px">
                            <h2 style="margin:0 0 10px;font-size:21px">Destaque principal</h2>
                            <p style="font-size:15px;line-height:1.7;color:#5f6b7a">Escreva a noticia principal e convide o leitor para continuar.</p>
                            <hr style="border:0;border-top:1px solid #e5e7eb;margin:26px 0">
                            <h3 style="margin:0 0 8px;font-size:17px">Segundo bloco</h3>
                            <p style="font-size:14px;line-height:1.7;color:#5f6b7a">Inclua outro aviso, link ou atualizacao importante.</p>
                        </td>
                    </tr>
                </table>
            </div>`,
        invite: `
            <div style="margin:0;background:#f4f6fb;padding:30px 12px;font-family:Arial,sans-serif;color:#263238">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:660px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden">
                    <tr>
                        <td style="padding:38px;text-align:center">
                            <div style="display:inline-block;padding:7px 12px;border-radius:999px;background:#eef2ff;color:#224abe;font-size:12px;font-weight:bold;text-transform:uppercase">Convite</div>
                            <h1 style="margin:18px 0 10px;font-size:32px;line-height:1.15">Voce esta convidado</h1>
                            <p style="font-size:16px;line-height:1.7;color:#5f6b7a;margin:0 0 24px">Ola {{name}}, insira aqui as informacoes do encontro, data e detalhes principais.</p>
                            <a href="https://salvaimerainha.org.br" style="display:inline-block;background:#1cc88a;color:#ffffff;text-decoration:none;padding:13px 24px;border-radius:8px;font-weight:bold">Confirmar presenca</a>
                        </td>
                    </tr>
                </table>
            </div>`
    };

    function updatePreview() {
        const html = $('#html_body').summernote('code');
        const subject = document.getElementById('subject').value || 'Assunto da campanha';
        const preheader = document.getElementById('preheader').value || '';
        const frame = document.getElementById('emailPreview');
        const documentHtml = `
            <!doctype html>
            <html>
            <head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
            <body style="margin:0;background:#f3f4f6">
                <div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:14px 18px;font-family:Arial,sans-serif">
                    <strong style="display:block;color:#111827">${subject}</strong>
                    <span style="display:block;color:#667085;font-size:13px;margin-top:4px">${preheader}</span>
                </div>
                ${html}
            </body>
            </html>`;

        frame.contentWindow.document.open();
        frame.contentWindow.document.write(documentHtml);
        frame.contentWindow.document.close();
    }

    function insertVariable(name) {
        $('#html_body').summernote('pasteHTML', '{{' + name + '}}');
        updatePreview();
    }

    $(function () {
        $('#html_body').summernote({
            height: 520,
            minHeight: 360,
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'table', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: updatePreview,
                onImageUpload: function (files) {
                    Array.from(files).forEach(function (file) {
                        const data = new FormData();
                        data.append('image', file);

                        fetch(window.emailEditorConfig.uploadUrl, {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': window.emailEditorConfig.csrf},
                            body: data
                        })
                            .then(response => response.json())
                            .then(payload => {
                                if (payload.url) {
                                    $('#html_body').summernote('insertImage', payload.url);
                                }
                            });
                    });
                }
            }
        });

        document.querySelectorAll('[data-template]').forEach(function (button) {
            button.addEventListener('click', function () {
                const html = templates[this.dataset.template];
                $('#html_body').summernote('code', html);
                updatePreview();
            });
        });

        document.querySelectorAll('[data-variable]').forEach(function (button) {
            button.addEventListener('click', function () {
                insertVariable(this.dataset.variable);
            });
        });

        document.querySelectorAll('[data-preview-size]').forEach(function (button) {
            button.addEventListener('click', function () {
                document.querySelectorAll('[data-preview-size]').forEach(item => item.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('emailPreview').classList.toggle('mobile', this.dataset.previewSize === 'mobile');
            });
        });

        document.querySelector('form.form-shell').addEventListener('submit', function () {
            document.getElementById('html_body').value = $('#html_body').summernote('code');
        });

        document.getElementById('subject').addEventListener('input', updatePreview);
        document.getElementById('preheader').addEventListener('input', updatePreview);
        updatePreview();
    });
</script>
@endverbatim
@endpush

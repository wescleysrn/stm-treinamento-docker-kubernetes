import os
from http.server import HTTPServer, BaseHTTPRequestHandler

LOG_LEVEL = os.getenv("WORKER_LOG_LEVEL", "INFO")

class SimpleWorkerHandler(BaseHTTPRequestHandler):
    def do_GET(self):
        self.send_response(200)
        self.send_header('Content-Type', 'text/plain; charset=utf-8')
        self.end_headers()
        response = f"Worker Interno Respondendo! [LogLevel: {LOG_LEVEL}]"
        self.wfile.write(response.encode('utf-8'))

if __name__ == '__main__':
    print(f"Iniciando Worker na porta interna 8000 (LogLevel: {LOG_LEVEL})...")
    server = HTTPServer(('0.0.0.0', 8000), SimpleWorkerHandler)
    server.serve_forever()

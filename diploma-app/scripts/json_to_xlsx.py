import json
import sys
from openpyxl import Workbook


def main() -> int:
    if len(sys.argv) < 3:
        print("JSON input path and XLSX output path are required", file=sys.stderr)
        return 1

    json_path = sys.argv[1]
    xlsx_path = sys.argv[2]

    with open(json_path, "r", encoding="utf-8") as source:
        payload = json.load(source)

    headers = payload.get("headers", [])
    rows = payload.get("rows", [])

    workbook = Workbook()
    sheet = workbook.active
    sheet.title = "Processed Data"

    if headers:
        sheet.append(headers)

    for row in rows:
        sheet.append(row)

    workbook.save(xlsx_path)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
